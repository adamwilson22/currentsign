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


use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\HtmlString;   
    
class FolderController extends Controller
{
    public function getdashboard(Request $request){
        if (!Auth::guard('api')->check()) {
        return $this->sendError(
            $result = null,
            $message = 'Unauthorized.',
            $notification = [],
            $error = [],
            $respose_code = 401
        );
    }
    
     $folderscount =  DB::table('folders')->count();
     $filescount =  DB::table('files')->count();
     $notescount =  DB::table('notes')->count();
     $pdfscount = 0;
     $data = [
         'folder_count' => $folderscount,
         'file_count' => $filescount,
         'note_count' => $notescount,
         'pdf_count' => $pdfscount,
         ];
         
     return $this->sendResponse($result = $data, $message = 'successfully.', $notification = null, $error = null, $respose_code = 200);  
    }
    
    public function create_note(Request $request){
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
    $data = $request->all();
    $data['user_id'] = $user->id;
    DB::table('notes')->insert($data);
    return $this->sendResponse($result = null, $message = 'Note Created successfully.', $notification = null, $error = null, $respose_code = 200);  
    }
    
     public function note(Request $request){
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
    $note = DB::table('notes')->where('id', $request->id)->first();
    return $this->sendResponse($result = $note, $message = 'successfully.', $notification = null, $error = null, $respose_code = 200);  
    }
    
    public function delete_note(Request $request){
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
    $note = DB::table('notes')->where('id', $request->id)->delete();
    return $this->sendResponse($result = null, $message = 'Note has been deleted successfully.', $notification = null, $error = null, $respose_code = 200);  
    }
    
    public function delete_folder(Request $request){
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
    $note = DB::table('folders')->where('id', $request->id)->delete();
    return $this->sendResponse($result = null, $message = 'Folder has been deleted successfully.', $notification = null, $error = null, $respose_code = 200);  
    }
    
    public function delete_File(Request $request){
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
    $note = DB::table('files')->where('id', $request->id)->delete();
    return $this->sendResponse($result = null, $message = 'File has been deleted successfully.', $notification = null, $error = null, $respose_code = 200);  
    }
    
     public function update_note(Request $request){
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
    $data = $request->all();
    $note = DB::table('notes')->where('id', $data['id'])->update($data);
    return $this->sendResponse($result = null, $message = 'Note has been updated successfully.', $notification = null, $error = null, $respose_code = 200);  
    }
    
    public function update_folder(Request $request){
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
    $data = $request->all();
    $note = DB::table('folders')->where('id', $data['id'])->update($data);
    return $this->sendResponse($result = null, $message = 'Folder has been updated successfully.', $notification = null, $error = null, $respose_code = 200);  
    }
    
     public function notes(Request $request){
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
    $notes = DB::table('notes')->get();
    return $this->sendResponse($result = $notes, $message = 'successfully.', $notification = null, $error = null, $respose_code = 200);  
    }
    
    public function get_list(Request $request){
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
    $folders = DB::table('folders')->get();
    foreach($folders as &$folder){
       $files = DB::table('files')->where('folder_id', $folder->id)->get();
       foreach($files as &$file){
           $file->file_path =  asset('/public/'.$file->file_path);
       }
       $folder->files = $files;
    }
    return $this->sendResponse($result = $folders, $message = 'successfully.', $notification = null, $error = null, $respose_code = 200);  
    }
    
    
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
         'folder_id' => 'required|exists:folders,id',
        'file' => 'required|file|max:10240', // Max file size 10MB
    ]);

  $user = Auth::guard('api')->user();
  $folder = DB::table('folders')->where('id', $request->folder_id)->where('user_id', $user->id)->first();

 if (!$folder) {
     return $this->sendError(null, 'Folder not found.', [], [], 404);
 }

// Store file
$file = $request->file('file');
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = 'uploads/folders/'.$folder->id.'/';
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
    'folder_id' => $folder->id,
    'file_name' => $fileName,
    'file_path' => $filePath . $fileName, // Path in public folder
    'file_type' => $fileType, // Store file type
]);

return $this->sendResponse($result = [
    'file_name' => $fileName,
    'file_url' => asset('/public/'.$filePath.$fileName), // URL accessible from browser
], $message = 'File uploaded successfully.', $notification = null, $error = null, $response_code = 200);

   }

    
    public function create_folder(Request $request){
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
    $data = $request->all();
    $data['user_id'] = $user->id;
    DB::table('folders')->insert($data);
    return $this->sendResponse($result = null, $message = 'Folder created successfully.', $notification = null, $error = null, $respose_code = 200);  
    }
    
    public function get_folder_files(Request $request)
   {
    if (!Auth::guard('api')->check()) {
        return $this->sendError(
            null,
            'Unauthorized.',
            [],
            [],
            401
        );
    }

    $user = Auth::guard('api')->user();
    $folder = DB::table('folders')->where('id', $request->folder_id)->first();
    if (!$folder) {
        return $this->sendError(null, 'Folder not found.', [], [], 404);
    }
    // Get files inside the folder
    $files = DB::table('files')->where('folder_id', $folder->id)->get();
    foreach ($files as &$file) {
          $file->file_path =  asset('/public/'.$file->file_path);
    }
    // Get subfolders inside the folder
    $subfolders = DB::table('folders')->where('parent_id', $folder->id)->get();
    $folder->files = $files;
    $folder->subfolders = $subfolders;
    return $this->sendResponse($folder, 'Retrieved folder contents successfully.', null, null, 200);
   }
    
    
    /*========================================================================================================*/
    
    public function submitSignature(Request $request)
{
    
    
    
    
    // Check authentication
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

    $user_id = $user->id;
    $user = DB::table('users')->where('id', $user_id)->first();
  
    
    

    if ($user->is_trial == 'false') {
        return $this->sendError(
            $result = null,
            $message = 'Trial expired. Please upgrade your account.',
            $notification = [],
            $error = [],
            $respose_code = 403
        );
    }

    // Validate file upload
    if (!$request->hasFile('pdf_path')) {
        return $this->sendError(
            $result = null,
            $message = 'No file uploaded.',
            $notification = [],
            $error = [],
            $respose_code = 400
        );
    }

    $file = $request->file('pdf_path');
    $fileName = time() . '_' . $file->getClientOriginalName();
    $filePath = 'uploads/folders/';

    // Ensure directory exists
    $destinationPath = public_path($filePath);
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    // Move the file
    $file->move($destinationPath, $fileName);

    // Prepare data for insertion (exclude 'pdf_path' from request to avoid unknown column)
    $data = $request->except(['_token', 'pdf_path']);
    $data['pdf_path'] = $filePath . $fileName;
    $data['user_id'] = $user_id;
    $data['page'] = $request->page;
    // Insert into database
    $id = DB::table('signatures')->insertGetId($data);

    // Check trial count
    $count = DB::table('signatures')->where('user_id', $user_id)->count();
    if ($count >= 5) {
        DB::table('users')->where('id', $user_id)->update(['is_trial' => 'false']);
    }

    // Send email
    if ($request->has('email')) {
        $subject = "Signature PDF";
        $emailid = $request->email;
        $link = url('/signature?id=' . $id);
        $message = "Hi,<br><br>Please sign this document.<br><a href='" . $link . "'>View Document</a>";

        $headers = "From: sign@currentsign.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

       mail($emailid, $subject, $message, $headers);
  

    // Return API response
    return $this->sendResponse(
        $result = ['signature_id' => $id, 'pdf_path' => asset($filePath . $fileName)],
        $message = 'Signature uploaded and email sent successfully.',
        $notification = null,
        $error = null,
        $respose_code = 200
    );
}

}




public function getSignedSignatures(Request $request)
{
    // 1️⃣ Authentication check
    if (!Auth::guard('api')->check()) {
        return $this->sendError(
            $result = null,
            $message = 'Unauthorized.',
            $notification = [],
            $error = [],
            $respose_code = 401
        );
    }

    $user_id = Auth::guard('api')->id();

    // 2️⃣ Fetch signed signatures
    $signeds = DB::table('signatures')
        ->where('user_id', $user_id)
        ->where('status', 'Signed')
        ->orderBy('id', 'desc')
        ->get();


  // 3️⃣ Convert pdf_path to full URL
    $signeds = $signeds->map(function ($file) {
        $file->signature = asset('/public/'.$file->signature);
        return $file;
    });
 


    // 3️⃣ Check if any signed signatures exist
    if ($signeds->isEmpty()) {
        return $this->sendResponse(
            $result = [],
            $message = 'No signed signatures found.',
            $notification = null,
            $error = null,
            $respose_code = 200
        );
    }

    // 4️⃣ Return API response
    return $this->sendResponse(
        $result = $signeds,
        $message = 'Signed signatures fetched successfully.',
        $notification = null,
        $error = null,
        $respose_code = 200
    );
}






    
}