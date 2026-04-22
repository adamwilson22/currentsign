<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use DB;
use Illuminate\Support\Facades\File;
    
    
class DocumentController extends Controller
{
   
 public function uploadFile(Request $request)
   {
    // Check if user is authenticated
    if (!Auth::guard('api')->check()) {
        return $this->sendError(
            null,
            'Unauthorized.',
            [],
            [],
            401
        );
    }

    // Validate request
    $request->validate([
        // 'folder_id' => 'required|exists:folders,id',
        'file' => 'required|file|max:10240', // Max file size 10MB
    ]);

  $user = Auth::guard('api')->user();
// $folder = DB::table('folders')->where('id', $request->folder_id)->where('user_id', $user->id)->first();

// if (!$folder) {
//     return $this->sendError(null, 'Folder not found.', [], [], 404);
// }

// Store file
$file = $request->file('file');
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = 'uploads/document/';
$fileType = $file->getClientMimeType(); // Get file MIME type

// Ensure the directory exists
$destinationPath = public_path($filePath);
if (!File::exists($destinationPath)) {
    File::makeDirectory($destinationPath, 0755, true, true);
}

// Move file to the public folder
$file->move($destinationPath, $fileName);

// Save file info to DB
DB::table('files')->insert([
    'user_id' => $user->id,
    'folder_id' => 2,
    'file_name' => $fileName,
    'file_path' => $filePath . $fileName, // Path in public folder
    'file_type' => $fileType, // Store file type
]);

return $this->sendResponse($result = [
    'file_name' => $fileName,
    'file_url' => asset('/public/'.$filePath.$fileName), // URL accessible from browser
], $message = 'File uploaded successfully.', $notification = null, $error = null, $response_code = 200);

   }


   public function get_document_list(Request $request) {
    if (!Auth::guard('api')->check()) {
        return $this->sendError(
            $result = null,
            $message = 'Unauthorized.',
            $notification = [],
            $error = [],
            $respose_code = 401
        );
    }

    $user = Auth::guard('api')->user();

    // Fetch user files
    $files = DB::table('files')->where('user_id', $user->id)->get();

    // Update file paths to be accessible URLs
    foreach ($files as &$file) {
        $file->file_path = asset('public/'.$file->file_path); 
    }
    


    return $this->sendResponse(
        $result = $files, 
        $message = 'Successfully fetched documents.', 
        $notification = null, 
        $error = null, 
        $respose_code = 200
    );
}







}