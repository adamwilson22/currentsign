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

     $user = Auth::guard('api')->user();
     $userId = $user->id;

     $fileCount = DB::table('files')->where('user_id', $userId)->count();
     $noteCount = DB::table('notes')->where('user_id', $userId)->count();
     $awaiting = DB::table('signatures')
         ->where('user_id', $userId)
         ->whereRaw('LOWER(status) = ?', ['awaiting'])
         ->count();
     $signed = DB::table('signatures')
         ->where('user_id', $userId)
         ->whereRaw('LOWER(status) = ?', ['signed'])
         ->count();

     $mapSignature = function ($row) {
         $pdf = $this->publicAssetUrl($row->signature ?: $row->pdf_path);
         $row->pdf_url = $pdf;
         $row->pdf_path = $this->publicAssetUrl($row->pdf_path);
         $row->signature = $this->publicAssetUrl($row->signature);
         $row->sign_url = url('/signature?id=' . $row->id);
         $row->edit_url = url('/edit-pdf/' . $row->id);
         return $row;
     };

     $awaitings = DB::table('signatures')
         ->where('user_id', $userId)
         ->whereRaw('LOWER(status) = ?', ['awaiting'])
         ->orderByDesc('id')
         ->limit(20)
         ->get()
         ->map($mapSignature);

     $signeds = DB::table('signatures')
         ->where('user_id', $userId)
         ->whereRaw('LOWER(status) = ?', ['signed'])
         ->orderByDesc('id')
         ->limit(20)
         ->get()
         ->map($mapSignature);

     $data = [
         'folder_count' => DB::table('folders')->where('user_id', $userId)->count(),
         'file_count' => $fileCount,
         'note_count' => $noteCount,
         'awaiting' => $awaiting,
         'signed' => $signed,
         'pdf_count' => $signed,
         'awaitings' => $awaitings,
         'signeds' => $signeds,
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
    $id = DB::table('notes')->insertGetId([
        'user_id' => $user->id,
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $note = DB::table('notes')->where('id', $id)->first();
    return $this->sendResponse($result = $note, $message = 'Note Created successfully.', $notification = null, $error = null, $respose_code = 200);  
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
    $note = DB::table('notes')->where('id', $request->id)->where('user_id', $user->id)->first();
    if (!$note) {
        return $this->sendError(null, 'Note not found.', null, null, 404);
    }
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
    $deleted = DB::table('notes')->where('id', $request->id)->where('user_id', $user->id)->delete();
    if (!$deleted) {
        return $this->sendError(null, 'Note not found.', null, null, 404);
    }
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
    $note = DB::table('files')->where('id', $request->id)->where('user_id', $user->id)->delete();
    if (!$note) {
        return $this->sendError(null, 'File not found.', null, null, 404);
    }
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
    $data = $request->except(['_token', 'user_id']);
    if (empty($data['id'])) {
        return $this->sendError(null, 'Note id is required.', null, null, 422);
    }
    $note = DB::table('notes')->where('id', $data['id'])->where('user_id', $user->id)->first();
    if (!$note) {
        return $this->sendError(null, 'Note not found.', null, null, 404);
    }
    unset($data['id']);
    DB::table('notes')->where('id', $note->id)->where('user_id', $user->id)->update($data);
    $updated = DB::table('notes')->where('id', $note->id)->first();
    return $this->sendResponse($result = $updated, $message = 'Note has been updated successfully.', $notification = null, $error = null, $respose_code = 200);  
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
    $notes = DB::table('notes')->where('user_id', $user->id)->orderByDesc('id')->get();
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
    $data['page'] = $request->page ?? 1;
    $data['status'] = 'Awaiting';
    // Insert into database
    $id = DB::table('signatures')->insertGetId($data);

    // Check trial count
    $count = DB::table('signatures')->where('user_id', $user_id)->count();
    if ($count >= 5) {
        DB::table('users')->where('id', $user_id)->update(['is_trial' => 'false']);
    }

    $link = url('/signature?id=' . $id);
    $mailOk = false;

    if ($request->filled('email')) {
        $subject = "Signature PDF";
        $emailid = $request->email;
        $message = "Hi,<br><br>Please sign this document.<br><a href='" . $link . "'>View Document</a>";

        $headers = "From: sign@currentsign.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        $mailOk = (bool) @mail($emailid, $subject, $message, $headers);
    }

    return $this->sendResponse(
        $result = [
            'signature_id' => $id,
            'pdf_path' => $this->publicAssetUrl($filePath . $fileName),
            'signing_link' => $link,
            'email_sent' => $mailOk,
        ],
        $message = $mailOk
            ? 'Signature uploaded and email sent successfully.'
            : 'Document saved. Use the signing link if email was not delivered.',
        $notification = null,
        $error = null,
        $respose_code = 200
    );
}

public function getAwaitingSignatures(Request $request)
{
    if (!Auth::guard('api')->check()) {
        return $this->sendError(null, 'Unauthorized.', [], [], 401);
    }

    $user_id = Auth::guard('api')->id();
    $rows = DB::table('signatures')
        ->where('user_id', $user_id)
        ->whereRaw('LOWER(status) = ?', ['awaiting'])
        ->orderByDesc('id')
        ->get()
        ->map(function ($file) {
            $file->pdf_path = $this->publicAssetUrl($file->pdf_path);
            $file->signature = $this->publicAssetUrl($file->signature);
            $file->pdf_url = $file->pdf_path;
            $file->sign_url = url('/signature?id=' . $file->id);
            $file->edit_url = url('/edit-pdf/' . $file->id);
            return $file;
        });

    return $this->sendResponse($rows, 'Awaiting signatures fetched successfully.', null, null, 200);
}

public function getNotifications(Request $request)
{
    if (!Auth::guard('api')->check()) {
        return $this->sendError(null, 'Unauthorized.', [], [], 401);
    }

    $user = Auth::guard('api')->user();
    if (!\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
        return $this->sendResponse([], 'No notifications.', null, null, 200);
    }

    $rows = DB::table('notifications')
        ->where('user_id', $user->id)
        ->orderByDesc('notification_id')
        ->limit(50)
        ->get();

    return $this->sendResponse($rows, 'Notifications fetched successfully.', null, null, 200);
}

public function markNotificationsRead(Request $request)
{
    if (!Auth::guard('api')->check()) {
        return $this->sendError(null, 'Unauthorized.', [], [], 401);
    }

    if (!\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
        return $this->sendResponse(null, 'OK.');
    }

    $userId = Auth::guard('api')->id();
    $id = $request->input('id') ?? $request->input('notification_id');
    $q = DB::table('notifications')->where('user_id', $userId);
    if ($id) {
        $q->where('notification_id', $id);
    }
    $q->update(['is_read' => true, 'updated_at' => now()]);

    return $this->sendResponse(null, 'Notifications marked read.');
}

public function getSignedSignatures(Request $request)
{
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

    $signeds = DB::table('signatures')
        ->where('user_id', $user_id)
        ->whereRaw('LOWER(status) = ?', ['signed'])
        ->orderBy('id', 'desc')
        ->get()
        ->map(function ($file) {
            $file->pdf_path = $this->publicAssetUrl($file->pdf_path);
            $file->signature = $this->publicAssetUrl($file->signature);
            $file->pdf_url = $file->signature ?: $file->pdf_path;
            $file->sign_url = url('/signature?id=' . $file->id);
            $file->edit_url = url('/edit-pdf/' . $file->id);
            return $file;
        });

    return $this->sendResponse(
        $result = $signeds,
        $message = $signeds->isEmpty() ? 'No signed signatures found.' : 'Signed signatures fetched successfully.',
        $notification = null,
        $error = null,
        $respose_code = 200
    );
}
    
}