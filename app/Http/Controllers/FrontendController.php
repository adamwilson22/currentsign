<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DB;
class FrontendController extends Controller {

    public function index() {
         return view('frontend/index');
    }
    
    public function login() {
         if (Auth::check()) {
        return redirect('/profile'); // Redirect to profile if already logged in
    }
         return view('frontend/login');
    }
    
    public function submit(Request $request)
{
    // Validate inputs
    $request->validate([
        'signature_data' => 'required',
        'x' => 'required|numeric',
        'y' => 'required|numeric',
        'scale' => 'required|numeric',
        'page' => 'required|integer',
    ]);

    // Paths
    $originalPdfPath = public_path('uploads/folders/8/1744782451_C-Interview-PDF (4).pdf');
    $signatureData = $request->input('signature_data');
    $x = floatval($request->input('x'));
    $y = floatval($request->input('y'));
    $scale = floatval($request->input('scale'));
    $page = intval($request->input('page'));

    // Convert base64 to image and save temporarily
    $imageData = explode(',', $signatureData)[1];
    $image = base64_decode($imageData);
    $signatureFilename = 'signature_' . time() . '.png';
    $tempImagePath = storage_path('app/public/signatures/' . $signatureFilename);
    file_put_contents($tempImagePath, $image);

    // Create output PDF path
    $outputFilename = 'signed_pdf_' . time() . '.pdf';
    $outputPath = storage_path('app/public/signed/' . $outputFilename);

    // Create FPDI instance
    $pdf = new Fpdi();
    $pageCount = $pdf->setSourceFile($originalPdfPath);

    for ($i = 1; $i <= $pageCount; $i++) {
        $tplId = $pdf->importPage($i);
        $size = $pdf->getTemplateSize($tplId);

        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($tplId);

        // Add signature only to the selected page
        if ($i === $page) {
            $sigWidth = 50 * $scale; // base size * scale
            $sigHeight = 20 * $scale;

            // Convert browser click (px) to mm (PDF units)
            $dpi = 96; // typical screen DPI
            $pxToMm = 25.4 / $dpi;
            $sigX = $x * $pxToMm;
            $sigY = $y * $pxToMm;

            $pdf->Image($tempImagePath, $sigX, $sigY, $sigWidth, $sigHeight);
        }
    }

    $pdf->Output($outputPath, 'F');

    // Return download link or path
    return response()->download($outputPath)->deleteFileAfterSend();
}


public function submitsignacture(Request $request){
    
$file = $request->file('file');
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
DB::table('signatures')->insert($data);
return redirect()->back()->with('success', 'Send successfully!');
}
    
    public function register() {
        if (Auth::check()) {
        return redirect('/profile'); // Redirect to profile if already logged in
       }
         return view('frontend/register');
    }
    
    public function submit_contact(Request $request){
        $data = $request->except('_token');
        DB::table('contacts')->insertGetId($data);
        return redirect()->back()->with('success', 'Send successfully!');
    }
    
    public function subscribe(Request $request)
   {
    $data = $request->except('_token');
    // Insert email into the database or perform other logic
    DB::table('subscribe_emails')->insert($data);
    // Return a success response
    return response()->json(['message' => 'Subscribed successfully!']);
   }
   
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required'
        ]);

        // Find user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
           return back()->with('error', 'User not found.');
        }

        // Generate 6-digit OTP
        $otp = 9999;

        // Store OTP in session (you can also store it in DB)
        Session::put('otp', $otp);
        Session::put('otp_email', $request->email);
        Session::put('otp_expires_at', now()->addMinutes(10)); // OTP expires in 10 minutes

         return redirect()->route('otp.verify.form', ['email' => $request->email])->with('success', 'OTP has been sent to your email.');
    }
    
    public function verifyOtp(Request $request)
    {
    $request->validate([
        'otp' => 'required',
    ]);

    $storedOtp = Session::get('otp');
    $storedEmail = Session::get('otp_email');
    $otpExpiresAt = Session::get('otp_expires_at');

    if (!$storedOtp || !$storedEmail || now()->greaterThan($otpExpiresAt)) {
        return back()->with('error', 'OTP expired. Please request a new one.');
    }

    if ($request->otp != $storedOtp) {
        return back()->with('error', 'Invalid OTP. Please try again.');
    }

    // OTP is valid - clear it from session
    Session::forget(['otp',  'otp_expires_at']);

    // Redirect to create password form
    return redirect()->route('password.create.form')->with('success', 'OTP verified. Set your new password.');
    }
    
    public function setPassword(Request $request)
    {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();
   //dd($user);
    if (!$user) {
       return back()->with('error','User not found.');
    }

    $user->password = Hash::make($request->password);
    $user->save();
    Session::forget(['otp_email']);
    return redirect()->route('login')->with('success', 'Password changed successfully. You can now log in.');
    }

    
}