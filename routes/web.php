<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminFormController;
use App\Http\Controllers\Admin\XcrudController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/clear', function () {

    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');

    return "✅ All cache cleared successfully!";
});











Route::get('/dump-autoload', function () {
     $output = shell_exec('composer dump-autoload 2>&1');
    //Artisan::call('dump-autoload');
    return nl2br($output);
});

Route::get('/', [FrontendController::class, 'index']);
Route::get('/pricing', function () {
    return view('frontend/pricing');
});
Route::get('/contact', function () {
    return view('frontend/contact');
});

Route::post('/contact-submit', function (Request $request) {

    DB::table('contact')->insert([
        'name'       => $request->name,
        'email'      => $request->email,
        'message'    => $request->message,
    ]);
    return redirect()->back()->with('success', 'Contact submitted successfully!');
});
Route::get('/privacy-policy', function () {
    return view('frontend/privacy-policy');
});
Route::get('/termsofuse', function () {
    return view('frontend/termsofuse');
});
Route::get('/signature', function (Request $request) {
    $id = $request->query('id');
    $signature = $id ? DB::table('signatures')->where('id', $id)->first() : null;
    if (! $signature) {
        abort(404, 'Document not found.');
    }
    return view('frontend/pdf-signacture', compact('signature'));
});

Route::get('/edit-pdf/{id}', function ($id, Request $request) {
    $signature = DB::table('signatures')->where('id', $id)->first();
    if (! $signature) {
        abort(404, 'Document not found.');
    }
    return view('frontend/edit-pdf', compact('signature'));
});

Route::get('/legal-disclaimer', function () {
    return view('frontend/legal-disclaimer');
});
Route::get('/forget', function () {
    return view('frontend/forget');
});
Route::get('/verify-otp', function () {
    return view('frontend/otp_verify');
})->name('otp.verify.form');
Route::post('/verify-otp', [FrontendController::class, 'verifyOtp'])->name('verify.otp');
Route::get('/create-password', function () {
    return view('frontend/create_password');
})->name('password.create.form');

Route::post('/set-password', [FrontendController::class, 'setPassword'])->name('set.password');

Route::get('/login', [FrontendController::class, 'login'])->name('login');
Route::post('/sendotp', [FrontendController::class, 'sendOtp']);
Route::post('/upload-file', [UserAuthController::class, 'uploadfile']);
Route::get('/register', [FrontendController::class, 'register']);
Route::post('/submit-contact', [FrontendController::class, 'submit_contact']);
Route::post('/subscribe-endpoint', [FrontendController::class, 'subscribe']);
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/register-user', [UserAuthController::class, 'postRegistration']);
Route::post('user/signacturesubmit/{id}', [UserAuthController::class, 'submit']);

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserAuthController::class, 'profile']);
    Route::get('/logout', [UserAuthController::class, 'logout']);
    Route::get('user/project-list', [UserAuthController::class, 'projects']);
    Route::get('user/item-list/{id}', [UserAuthController::class, 'items']);
    Route::get('user/item-details/{id}', [UserAuthController::class, 'item_details']);
    Route::get('user/edit-item/{id}', [UserAuthController::class, 'edit_item']);
    Route::post('user/update-item/{id}', [UserAuthController::class, 'update_item']);
    Route::get('user/create-item/{id}', [UserAuthController::class, 'create_item']);
    Route::post('user/save-item/{id}', [UserAuthController::class, 'save_item']);
    Route::get('user/create-project', [UserAuthController::class, 'create_project']);
    Route::post('user/save-project', [UserAuthController::class, 'save_project']);
    Route::post('user/update-profile', [UserAuthController::class, 'update_profile']);
    Route::post('user/change-password', [UserAuthController::class, 'changePassword']);
    Route::get('user/contacts', [UserAuthController::class, 'contacts']);
    Route::get('user/create-contact', [UserAuthController::class, 'create_contact']);
    Route::get('user/edit-contact/{id}', [UserAuthController::class, 'edit_contact']);
    Route::post('user/update-contact/{id}', [UserAuthController::class, 'update_contact']);
    Route::get('user/delete-contact/{id}', [UserAuthController::class, 'delete_contact']);
    Route::post('user/save-contact', [UserAuthController::class, 'save_contact']);
    Route::get('user/company-detail', [UserAuthController::class, 'company_detail']);
    Route::get('user/dashboard', [UserAuthController::class, 'dashboard']);
    
    Route::post('user/save-doc', [UserAuthController::class, 'savedoc']);
    Route::post('user/submitsignacture', [UserAuthController::class, 'submitsignacture']);

    Route::get('user/documents', [UserAuthController::class, 'documents']);
    Route::get('user/delete-doc/{id}', [UserAuthController::class, 'deletedoc']);
    
    
    Route::get('user/notes', [UserAuthController::class, 'notes']);
    Route::get('user/add-note', [UserAuthController::class, 'addnote']);
    Route::post('user/save-note', [UserAuthController::class, 'savenote']);
    Route::get('user/edit-note/{id}', [UserAuthController::class, 'editnote']);
    Route::post('user/update-note/{id}', [UserAuthController::class, 'updatenote']);
    Route::get('user/delete-note/{id}', [UserAuthController::class, 'deletenote']);
    Route::get('user/notifications', [UserAuthController::class, 'notifications']);
    
    // In routes/web.php
    Route::post('/import-contacts', [UserAuthController::class, 'import'])->name('contacts.import');
});



Route::prefix('admin')->group(function () 
{

Route::get('/', [AdminAuthController::class, 'index']);
Route::get('login', [AdminAuthController::class, 'index'])->name('admin.login');
Route::post('admin-login', [AdminAuthController::class, 'postLogin'])->name('admin.login.post');

Route::group(['middleware' => 'admin'], function () {
    
 /***************AdminAuthController*****************/
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    /***************AdminAuthController*****************/

    /**************AdminPageController******************/
    Route::get('dashboard', [AdminPageController::class, 'dashboard'])->name('admin.dashboard');
    /**************AdminPageController******************/
    
    
    
    
    
        Route::get('customer-list', [AdminPageController::class, 'customers'])->name('customers');
        Route::get('customers_add', [AdminPageController::class, 'customers_add'])->name('customers.add');
        Route::get('customers_edit/{id}', [AdminPageController::class, 'customers_edit'])->name('customers.edit');
        Route::post('customers_save', [AdminPageController::class, 'customers_save'])->name('customers.save');
        Route::post('customers_update', [AdminPageController::class, 'customers_update'])->name('customers.update');
        Route::post('customers_edit/{id?}', [AdminPageController::class, 'delete_customer'])->name('customers.delete');
        
        
         Route::get('signatures/{id}', [AdminPageController::class, 'signatures'])->name('signatures');
        
   
});

/*Route::group(['middleware' => 'admin'], function () {

     Route::get('users/', [XcrudController::class, 'users']);

    

});*/


});








// test auto deploy
