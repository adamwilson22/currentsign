<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\File;


class DocumentController extends Controller
{

 public function uploadFile(Request $request)
   {
    if (!Auth::guard('api')->check()) {
        return $this->sendError(null, 'Unauthorized.', [], [], 401);
    }

    $request->validate([
        'file' => 'required|file|max:10240',
        'name' => 'nullable|string|max:255',
    ]);

    $user = Auth::guard('api')->user();

    $file = $request->file('file');
    $original = $file->getClientOriginalName();
    $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $original);
    $filePath = 'uploads/document/';
    $fileType = $file->getClientMimeType();

    $destinationPath = public_path($filePath);
    if (!File::exists($destinationPath)) {
        File::makeDirectory($destinationPath, 0755, true, true);
    }

    $file->move($destinationPath, $fileName);

    $relative = $filePath . $fileName;
    $displayName = $request->input('name') ?: $original;

    $id = DB::table('files')->insertGetId([
        'user_id' => $user->id,
        'folder_id' => $request->input('folder_id'),
        'file_name' => $displayName,
        'file_path' => $relative,
        'file_type' => $fileType,
    ]);

    return $this->sendResponse([
        'id' => $id,
        'file_name' => $displayName,
        'file_path' => $relative,
        'file_url' => $this->publicAssetUrl($relative),
    ], 'File uploaded successfully.', null, null, 200);
   }


   public function get_document_list(Request $request) {
    if (!Auth::guard('api')->check()) {
        return $this->sendError(null, 'Unauthorized.', [], [], 401);
    }

    $user = Auth::guard('api')->user();
    $files = DB::table('files')->where('user_id', $user->id)->orderByDesc('id')->get();

    foreach ($files as $file) {
        $file->file_url = $this->publicAssetUrl($file->file_path);
        $file->file_path = $file->file_url;
    }

    return $this->sendResponse(
        $files,
        'Successfully fetched documents.',
        null,
        null,
        200
    );
}

}
