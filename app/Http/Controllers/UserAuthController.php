<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use App\Models\Contact;
use App\Imports\ContactsImport;
use Spatie\SimpleExcel\SimpleExcelReader;
use Maatwebsite\Excel\Facades\Excel;
use Hash;
use DB;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class UserAuthController extends Controller {

    public function login(Request $request) {
        
        $request->validate(['email' => 'required', 'password' => 'required' ]);
        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('web')->attempt($credentials)) {
            return redirect()->intended('/profile')->withSuccess('You have Successfully logged in');
        }
        return redirect("/login")->withInput()->withErrors(['error' => 'Oops! You have entered invalid credentials']);
    }
    
    public function postRegistration(Request $request) {
   
    $request->validate([
        'full_name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6', // Validate password and confirmation match
    ]);

    // Process registration
    $data = $request->all();
    $check = $this->create($data);

    return redirect("/login")->withSuccess('Great! You have Successfully registered');
  }
  
  public function profile(){
       $menu = 'profile';
       return view('frontend.profile', compact('menu'));
  }
    
    
    public function create(array $data) {
        return User::create([
            'name' => $data['full_name'],
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    
    public function logout() {
        Session::flush();
        Auth::guard('web')->logout();
        return Redirect('/login');
    }
    
    public function update_profile(Request $request) {
        $user = Auth::user();
        $data = $request->all();
        $user->update($data);
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
    
    public function changePassword(Request $request)
    {
    // Validate the input
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|min:8',
    ]);
    // Check if the old password matches the current password
    if (!Hash::check($request->old_password, Auth::user()->password)) {
       return back()->withInput()->withErrors(['error' => 'The provided password does not match our records.']);
    }
    // Update the password
    Auth::user()->update([
        'password' => Hash::make($request->new_password),
    ]);
    // Redirect with success message
    return back()->with('success', 'Password updated successfully!');
    }
    
    public function projects(){
        $menu = 'projects';
        $projects = DB::table('projects')->where('user_id', auth()->id())->get();
        return view('frontend.project-list', compact('projects', 'menu'));
    }
    
    public function create_project(){
        $menu = 'projects';
        return view('frontend.create-project', compact('menu'));
    }
    
    public function dashboard(){
         $menu = 'dashboard';
         $user_id = auth()->id();
         $file_count = Schema::hasTable('files')
             ? DB::table('files')->where('user_id', $user_id)->count()
             : 0;
         $awaiting = Schema::hasTable('signatures')
             ? DB::table('signatures')->where('user_id', $user_id)->whereRaw('LOWER(status) = ?', ['awaiting'])->count()
             : 0;
         $signed = Schema::hasTable('signatures')
             ? DB::table('signatures')->where('user_id', $user_id)->whereRaw('LOWER(status) = ?', ['signed'])->count()
             : 0;
         $signeds = Schema::hasTable('signatures')
             ? DB::table('signatures')->where('user_id', $user_id)->whereRaw('LOWER(status) = ?', ['signed'])->orderBy('id', 'desc')->get()
             : collect();
         $awaitings = Schema::hasTable('signatures')
             ? DB::table('signatures')->where('user_id', $user_id)->whereRaw('LOWER(status) = ?', ['awaiting'])->orderBy('id', 'desc')->get()
             : collect();
         $note_count = Schema::hasTable('notes')
             ? DB::table('notes')->where('user_id', $user_id)->count()
             : 0;
         return view('frontend.dashboard', compact('menu', 'file_count', 'note_count', 'awaiting', 'signed', 'signeds', 'awaitings'));
    }
    
    public function documents(){
         $menu = 'documents';
         $user_id = auth()->id();
         if (!Schema::hasTable('files')) {
             $files = collect();
             return view('frontend.documents', compact('menu', 'files'))
                 ->withErrors(['error' => 'Documents table is missing. Please run database migrations.']);
         }

         $files = DB::table('files')->where('user_id', $user_id)->get();
         return view('frontend.documents', compact('menu', 'files'));
    }
    
public function submit(Request $request, $id)
{
    $request->validate([
        'signature_data' => 'required',
        'x' => 'required',
        'y' => 'required',
        'scale' => 'required',
        'canvas_height' => 'required|numeric',
    ]);

    $signature = DB::table('signatures')->where('id', $id)->first();
    if (!$signature) {
        return back()->with('error', 'Signature entry not found.');
    }

    $filePath = 'uploads/folders/';

    // Decode and save signature image
    $imageData = explode(',', $request->signature_data)[1];
    $image = base64_decode($imageData);
    $signatureFilename = 'signature_' . time() . '.png';
    $tempImagePath = public_path($filePath . $signatureFilename);
    file_put_contents($tempImagePath, $image);
    
    // Get original signature dimensions
    list($origWidth, $origHeight) = getimagesize($tempImagePath);

    // Update database
    DB::table('signatures')->where('id', $id)->update([
        'signature' => $filePath . $signatureFilename,
        'status' => 'Signed',
        'updated_at' => now(),
    ]);
    
   $signature = DB::table('signatures')->where('id', $id)->first();
   $user = DB::table('users')->where('id', $signature->user_id)->first();
    
     $subject = "signature pdf";
   $emailid = $user->email;
   // Email message
   $link =  url('/signature?id='.$id);
   $message = "Hi,<br><br>Check signatured Doc. <br><a href='".$link."'>View Document</a>";

    // Email headers
    $headers = "From: sign@currentsign.com\r\n";
   // $headers = "From: info@ryenbilvask.no\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Send the email
    mail($emailid, $subject, $message, $headers);

    // PDF processing
    $outputFilename = 'signed_pdf_' . time() . '.pdf';
    $outputPath = public_path($filePath . $outputFilename);

    $x = floatval($request->x);
    $y = floatval($request->y);
    $scale = floatval($request->scale);
    $canvasHeight = floatval($request->canvas_height);

    // Text positions
    $nameX = floatval($request->name_x ?? 0);
    $nameY = floatval($request->name_y ?? 0);
    $titleX = floatval($request->title_x ?? 0);
    $titleY = floatval($request->title_y ?? 0);
    $dateX = floatval($request->date_x ?? 0);
    $dateY = floatval($request->date_y ?? 0);

    $pdf = new Fpdi();
    $originalPdfPath = public_path($signature->pdf_path);
    $pageCount = $pdf->setSourceFile($originalPdfPath);

    for ($i = 1; $i <= $pageCount; $i++) {
        $tplId = $pdf->importPage($i);
        $size = $pdf->getTemplateSize($tplId);

        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($tplId);

        if ($i === 1) {
            // Calculate conversion factor (PDF points per canvas pixel)
            $scaleY = $size['height'] / $canvasHeight;
            
            // Signature positioning (direct point conversion)
            $sigX = $x * $scaleY +15;
            $sigY = $y * $scaleY +25;
            
            // Calculate scaled dimensions using actual image size
            $sigWidth = $origWidth * $scale * $scaleY;
            $sigHeight = $origHeight * $scale * $scaleY;
            
            $pdf->Image($tempImagePath, $sigX, $sigY, $sigWidth, $sigHeight);

            // Text positioning with baseline adjustment
            $fontSize = 12;
            $pdf->SetFont('Helvetica', '', $fontSize);
            $pdf->SetTextColor(0, 0, 0);

            if ($request->name) {
                $pdf->SetXY($nameX * $scaleY, $nameY * $scaleY + $fontSize);
                $pdf->Write(0, $request->name);
            }

            if ($request->title) {
                $pdf->SetXY($titleX * $scaleY, $titleY * $scaleY + $fontSize);
                $pdf->Write(0, $request->title);
            }

            if ($request->date) {
                $pdf->SetXY($dateX * $scaleY + 13, $dateY * $scaleY + 17 + $fontSize);
                $pdf->Write(0, $request->date);
            }
        }
    }

    $pdf->Output($outputPath, 'F');

    $signedRelativePath = $filePath . $outputFilename;
    DB::table('signatures')->where('id', $id)->update([
        'signature' => $signedRelativePath,
        'pdf_path' => $signedRelativePath,
        'status' => 'Signed',
        'updated_at' => now(),
    ]);

    $this->notifyDocumentOwnerSigned($id, $signedRelativePath);

    Session::flash('success', 'PDF signed successfully. Your download should start shortly.');
    return response()->download($outputPath);
}


public function submitsignacture_old(Request $request){
    
 $user_id = auth()->id();   
 $user = DB::table('users')->where('id', $user_id)->first(); 
 if($user->is_trial == 'false'){
    // return redirect()->back()->with('error', 'Trial expired. Please upgrade your account.');
 }
    
$file = $request->file('pdf_path');
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = 'uploads/folders/';
$fileType = $file->getClientMimeType(); // Get file MIME type

// Ensure the directory exists
$destinationPath = public_path($filePath);
// Move file to the public folder
$file->move($destinationPath, $fileName);
$data = $request->except('_token');
$data['pdf_path'] = $filePath . $fileName; // Path in public folder
$data['user_id'] = auth()->id();
$id = DB::table('signatures')->insertGetId($data);
$count = DB::table('signatures')->where('user_id', auth()->id())->count();
if($count >= 5){
   DB::table('users')->where('id', $user_id)->update(['is_trial' => 'false']); 
}

 $subject = "signature pdf";
 $emailid = $request->email;
 // Email message
 $link =  url('/signature?id='.$id);
 $message = "Hi,<br><br>please signature on this Doc. <br><a href='".$link."'>View Document</a>";

    // Email headers
    $headers = "From: sign@currentsign.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    
    
    // Send the email
    mail($emailid, $subject, $message, $headers);

return redirect()->back()->with('success', 'Send successfully!');
}



public function submitsignacture(Request $request)
{
    $user_id = auth()->id();
    $user = DB::table('users')->where('id', $user_id)->first();

    if ($user->is_trial == 'false') {
        // return redirect()->back()->with('error', 'Trial expired. Please upgrade your account.');
    }

    // ✅ PDF Upload
    $file = $request->file('pdf_path');
    $fileName = time() . '_' . $file->getClientOriginalName();
    $filePath = 'uploads/folders/';
    $destinationPath = public_path($filePath);
    $file->move($destinationPath, $fileName);

    // ✅ Save Data
    $data = $request->except('_token');
    $data['pdf_path'] = $filePath . $fileName;
    $data['user_id'] = $user_id;
    $data['status'] = 'Awaiting';

    $id = DB::table('signatures')->insertGetId($data);

    // ✅ Trial Count
    $count = DB::table('signatures')->where('user_id', $user_id)->count();
    if ($count >= 5) {
        DB::table('users')->where('id', $user_id)->update(['is_trial' => 'false']);
    }

    // ✅ Email Send (SMTP) — don't fail the whole submit if mail is unreachable
    $emailid = $request->email;
    $link = url('/signature?id=' . $id);
    $mailOk = false;

    if (! empty($emailid)) {
        try {
            Mail::send([], [], function ($message) use ($emailid, $link) {
                $message->to($emailid)
                    ->subject('Signature PDF')
                    ->from(config('mail.from.address', 'sign@currentsign.com'), config('mail.from.name', 'CurrentSign'))
                    ->html("
                        Hi,<br><br>
                        Please sign this document.<br><br>
                        <a href='{$link}' style='padding:10px 15px;background:#007bff;color:#fff;text-decoration:none;border-radius:5px;'>
                            View Document
                        </a>
                    ");
            });
            $mailOk = true;
        } catch (\Throwable $e) {
            \Log::warning('Signature invite email failed', [
                'signature_id' => $id,
                'email' => $emailid,
                'error' => $e->getMessage(),
            ]);
        }
    }

    $flash = $mailOk
        ? 'Document sent successfully to ' . $emailid . '.'
        : 'Document saved. Email could not be delivered — use the signing link below.';

    return redirect()->back()
        ->with('success', $flash)
        ->with('signing_link', $link);
}










public function uploadfile(Request $request)
{
    try {
        $request->validate([
            'id' => 'required|integer',
            'pdf_file' => 'required|file|max:51200',
        ]);
    } catch (\Throwable $e) {
        return response()->json(['message' => 'Invalid upload: ' . $e->getMessage()], 422);
    }

    $id = (int) $request->input('id');
    $signature = DB::table('signatures')->where('id', $id)->first();
    if (! $signature) {
        return response()->json(['message' => 'Document not found.'], 404);
    }

    $file = $request->file('pdf_file');
    if (! $file || ! $file->isValid()) {
        return response()->json(['message' => 'Signed PDF file is missing or invalid.'], 422);
    }

    $filePath = 'uploads/folders/';
    $destinationPath = public_path($filePath);
    if (! is_dir($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    $safeBase = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'signed';
    $fileName = 'signed_' . $id . '_' . time() . '_' . $safeBase . '.pdf';

    try {
        $file->move($destinationPath, $fileName);
    } catch (\Throwable $e) {
        \Log::error('Signed PDF upload failed', ['signature_id' => $id, 'error' => $e->getMessage()]);

        return response()->json(['message' => 'Could not save signed PDF on server.'], 500);
    }

    $signedRelativePath = $filePath . $fileName;
    DB::table('signatures')->where('id', $id)->update([
        'signature' => $signedRelativePath,
        'pdf_path' => $signedRelativePath,
        'status' => 'Signed',
        'updated_at' => now(),
    ]);

    $this->notifyDocumentOwnerSigned($id, $signedRelativePath);

    return response()->json([
        'message' => 'Signed document received. The sender can view it on their dashboard.',
        'path' => asset($signedRelativePath),
        'id' => $id,
        'status' => 'Signed',
    ]);
}

private function notifyDocumentOwnerSigned(int $id, string $signedRelativePath): void
{
    $signature = DB::table('signatures')->where('id', $id)->first();
    if (! $signature || ! $signature->user_id) {
        return;
    }

    $user = DB::table('users')->where('id', $signature->user_id)->first();
    if (! $user || empty($user->email)) {
        return;
    }

    $link = asset($signedRelativePath);
    $dashboardLink = url('/user/dashboard');

    if (Schema::hasTable('notifications')) {
        DB::table('notifications')->insert([
            'user_id' => $signature->user_id,
            'notification_text' => 'Document signed',
            'notification_message' => 'Document #' . $id . ' has been signed and is ready to view.',
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    try {
        Mail::send([], [], function ($message) use ($user, $link, $dashboardLink, $id) {
            $message->to($user->email)
                ->subject('Your document has been signed')
                ->from(config('mail.from.address', 'sign@currentsign.com'), config('mail.from.name', 'CurrentSign'))
                ->html("
                    Hi,<br><br>
                    Document #{$id} has been signed.<br><br>
                    <a href='{$link}'>View signed PDF</a><br>
                    <a href='{$dashboardLink}'>Open dashboard</a>
                ");
        });
    } catch (\Throwable $e) {
        \Log::warning('Signed document email failed', [
            'signature_id' => $id,
            'error' => $e->getMessage(),
        ]);

        $headers = "From: sign@currentsign.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        @mail(
            $user->email,
            'Your document has been signed',
            "Hi,<br><br>Document #{$id} has been signed.<br><a href='{$link}'>View signed PDF</a>",
            $headers
        );
    }
}

    
    public function deletedoc($id){
        if (!Schema::hasTable('files')) {
            return back()->withErrors(['error' => 'Documents table is missing. Please run database migrations.']);
        }
        DB::table('files')->where('id', $id)->delete();
        return back()->with('success', 'successfully!');
    }
    
    public function savedoc(Request $request){
         $menu = 'projects';
        if (!Schema::hasTable('files')) {
            return back()->withErrors(['error' => 'Documents table is missing. Please run database migrations.']);
        }
         
$file = $request->file('file');
$fileName = time() . '_' . $file->getClientOriginalName();
$filePath = 'uploads/folders/';
$fileType = $file->getClientMimeType(); // Get file MIME type

// Ensure the directory exists
$destinationPath = public_path($filePath);
// Move file to the public folder
$file->move($destinationPath, $fileName);
$data['file_name'] = $fileName;
$data['file_path'] = $filePath . $fileName; // Path in public folder
$data['file_type'] = $fileType; // Store file type
$data['user_id'] = auth()->id();
DB::table('files')->insert($data);

$arr['notification_text'] ='New Document Uploaded';
$arr['notification_message'] ='Your document "'.$fileName.'" has been successfully uploaded.';
$arr['user_id'] = auth()->id();
DB::table('notifications')->insert($arr);
return back()->with('success', 'successfully!');

    }
    
    public function notes(){
         $menu = 'notes';
         $user_id = auth()->id();
         if (!Schema::hasTable('notes')) {
             $notes = collect();
             return view('frontend.notes', compact('menu', 'notes'))
                 ->withErrors(['error' => 'Notes table is missing. Please run database migrations.']);
         }
         $notes = DB::table('notes')->where('user_id', $user_id)->get();
         return view('frontend.notes', compact('menu', 'notes'));
    }
    
    public function addnote(){
         $menu = 'notes';
         return view('frontend.add-note', compact('menu'));
    }
    
    public function editnote($id){
         $menu = 'notes';
         $note = DB::table('notes')->where('id', $id)->first();
         return view('frontend.edit-note', compact('menu', 'note'));
    }
    
    public function savenote(Request $request){
         $data = $request->except('_token');
         $data['user_id'] = auth()->id();
         DB::table('notes')->insert($data);
         return redirect("user/notes")->withSuccess('Created Successfully'); 
    }
    
    public function updatenote(Request $request, $id){
        $data = $request->except('_token');
        $note = DB::table('notes')->where('id', $id)->update($data);
         return redirect("user/notes")->withSuccess('Update Successfully'); 
    }
    
    public function deletenote($id){
        DB::table('notes')->where('id', $id)->delete();
        return redirect("user/notes")->withSuccess('delete Successfully'); 
    }
    
    public function notifications(){
         $menu = 'notifications';
         $user_id = auth()->id();
         if (!Schema::hasTable('notifications')) {
             $notifications = collect();
             return view('frontend.notification', compact('menu', 'notifications'))
                 ->withErrors(['error' => 'Notifications table is missing. Please run database migrations.']);
         }
         $notifications = DB::table('notifications')
    ->where('user_id', $user_id)
    ->orderBy('notification_id', 'desc') // or use 'created_at' if available
    ->limit(5)
    ->get();
         return view('frontend.notification', compact('menu', 'notifications'));
    }
    
    public function save_project(Request $request){
        $data = $request->except('_token');
    if ($request->hasFile('image')) {
    $image = $request->file('image');
    $uniqueName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
    $destinationPath = public_path('public/uploads'); // Specify the directory where you want to save the file
    
    $image->move($destinationPath, $uniqueName); // Move the uploaded file to the destination
    $data['image'] = 'uploads/' . $uniqueName; // Save the file path
    }
    $data['user_id'] = auth()->id();


        DB::table('projects')->insert($data);
        return redirect("user/project-list")->withSuccess('Created Successfully');
    }
    
    public function contacts(){
         $menu = 'contacts';
         $contacts = DB::table('build_contacts')->where('user_id', auth()->id())->get();
         return view('frontend.contacts', compact('contacts', 'menu'));
    }
    
    public function items($id){
         $menu = 'projects';
         $items = DB::table('items')->where('project_id', $id)->get();
         $project_id = $id;
         return view('frontend.item-list', compact('items', 'project_id', 'menu'));
    }
    
    public function item_details($id){
        $menu = 'projects';
        $item = DB::table('items')->where('id', $id)->first();
        return view('frontend.item-details', compact('item', 'menu'));
    }
    
    public function create_item($id){
         $menu = 'projects';
         $project_id = $id;
         return view('frontend.create-item', compact('project_id', 'menu'));
    }
    
    public function edit_item($id){
         $menu = 'projects';
         $project_id = $id;
         $item = DB::table('items')->where('id', $id)->first();
         return view('frontend.edit-item', compact('project_id', 'menu', 'item'));
    }
    
     public function update_item(Request $request, $id){
    $data = $request->except(['_token', 'redirect_type']);
   
    if ($request->hasFile('image')) {
    $image = $request->file('image');
    $uniqueName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
    $destinationPath = public_path('public/uploads'); // Specify the directory where you want to save the file
    
    $image->move($destinationPath, $uniqueName); // Move the uploaded file to the destination
    $data['image'] = 'uploads/' . $uniqueName; // Save the file path
    }

            DB::table('items')->where('id', $id)->update($data);
            return redirect("user/item-list/".$request->project_id)->withSuccess('Update Successfully');
        
    }
    
    
    
    public function save_item(Request $request, $id){
    $data = $request->except(['_token', 'redirect_type']);
   
    if ($request->hasFile('image')) {
    $image = $request->file('image');
    $uniqueName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
    $destinationPath = public_path('public/uploads'); // Specify the directory where you want to save the file
    
    $image->move($destinationPath, $uniqueName); // Move the uploaded file to the destination
    $data['image'] = 'uploads/' . $uniqueName; // Save the file path
    }
    
    $data['project_id'] = $id;


        DB::table('items')->insert($data);
        if($request->redirect_type =='list'){
            return redirect("user/item-list/".$id)->withSuccess('Created Successfully');
        }else{
            return redirect("user/create-item/".$id)->withSuccess('Created Successfully');
        }
        
    }
    
    public function create_contact(){
        $menu = 'contacts';
        return view('frontend.create-contact', compact('menu'));
    }
    
    public function edit_contact($id){
        $menu = 'contacts';
        $contact = DB::table('build_contacts')->where('id', $id)->first();
        return view('frontend.edit-contact', compact('menu', 'contact'));
    }
    
    public function update_contact(Request $request, $id){
        $menu = 'contacts';
        $data = $request->except('_token');
        DB::table('build_contacts')->where('id', $id)->update($data);
        return redirect("user/contacts")->withSuccess('Update Successfully');
    }
    
    public function delete_contact($id){
        DB::table('build_contacts')->where('id', $id)->delete();
        return redirect("user/contacts")->withSuccess('Delete Successfully');
    }
    
    public function save_contact(Request $request){
        $data = $request->except('_token');
        $data['user_id'] = auth()->id();
        DB::table('build_contacts')->insert($data);
         return redirect("user/contacts")->withSuccess('Created Successfully');
    }
    
    public function company_detail(){
        $menu = 'company-detail';
        return view('frontend.company-detail', compact('menu'));
    }
    
      public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $file = $request->file('file')->store('temp'); // Store temporarily
        $path = storage_path('app/' . $file);

        // Read file using SimpleExcelReader
        $rows = SimpleExcelReader::create($path)->getRows();

        // Insert rows into the database
        $rows->each(function ($row) {
            Contact::create([
                'company_name'        => $row['company'],
            'contect_name'  => $row['contacts_name'],
            'email'          => $row['email'],
            'phone_number'   => $row['phone_number'],
            'address'       => $row['location'],
            'user_id'        => Auth::id() // Add authenticated user's ID
            ]);
        });

        return back()->with('success', 'imported successfully.');
    }
    
}