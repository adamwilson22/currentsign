<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Automotive\Cars\CarController;
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\FavouriteController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\FolderController;

use App\Http\Controllers\PayPalController;



/*

|--------------------------------------------------------------------------

| API Routes

|--------------------------------------------------------------------------

|

| Here is where you can register API routes for your application. These

| routes are loaded by the RouteServiceProvider and all of them will

| be assigned to the "api" middleware group. Make something great!

|

*/
Route::prefix('auth')->group(function () {
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('get-profile', [AuthController::class, 'getProfile']);
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
    Route::post('get-users', [AuthController::class, 'getUsers']);
    Route::post('password-reset', [AuthController::class, 'passwordReset']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('delete-account', [AuthController::class, 'delete_account']);
    
    Route::post('create-new-password', [AuthController::class, 'createNewPassword']);
    Route::post('change-password', [AuthController::class, 'changePassword']);
    Route::post('create-new-password-without-login', [AuthController::class, 'createNewPasswordWithoutLogin']);
    //Route::post('forgot-password', [AuthController::class, 'forgotPassword'])
});


Route::prefix('folders')->group(function () {
     Route::post('get-list', [FolderController::class, 'get_list']);
     Route::post('get-folder-files', [FolderController::class, 'get_folder_files']);
     Route::post('file/upload', [FolderController::class, 'uploadFile']);
     Route::post('file/delete', [FolderController::class, 'delete_File']);
     Route::post('file/copy', [FolderController::class, 'copy_File']);
     Route::post('file/move', [FolderController::class, 'mony_File']);
     Route::post('create-folder', [FolderController::class, 'create_folder']);
     Route::post('update-folder', [FolderController::class, 'update_folder']);
     Route::post('delete_folder', [FolderController::class, 'delete_folder']);
     /*=======================================================================================================*/
     Route::post('submitSignature', [FolderController::class, 'submitSignature']);
     Route::post('getSignedSignatures', [FolderController::class, 'getSignedSignatures']);
     
     
});




/*======================================================================================================================================*/
/*======================================================================================================================================*/
/*======================================================================================================================================*/
/*======================================================================================================================================*/
/*======================================================================================================================================*/
/*======================================================================================================================================*/



Route::get('get_success_url', [PayPalController::class, 'successUrl'])->name('paypal.successUrl');

Route::get('add_payment_status_check', [PayPalController::class, 'add_payment_status_check'])->name('paypal.add_payment_status_check');

Route::get('/paypal/create-order', [PayPalController::class, 'createOrder'])->name('paypal.create');
Route::get('/paypal/success', [PayPalController::class, 'captureOrder'])->name('paypal.success');
Route::get('/paypal/cancel', function () {
     return redirect()->away('https://server-php-8-3.technorizen.com/cancel');
})->name('paypal.cancel');


Route::prefix('booking')->group(function () {
    Route::post('add_booking', [BookingController::class, 'add_booking']);
    Route::post('get_booking_list', [BookingController::class, 'get_booking_list']);
 
});




Route::prefix('home')->group(function () {
    Route::post('get-home', [HomeController::class, 'getHome']);
});

 Route::post('get-dashboard', [FolderController::class, 'getdashboard']);
 Route::post('create-note', [FolderController::class, 'create_note']);
 Route::post('notes', [FolderController::class, 'notes']);
 Route::post('note', [FolderController::class, 'note']);
 Route::post('delete-note', [FolderController::class, 'delete_note']);
 Route::post('update-note', [FolderController::class, 'update_note']);



Route::prefix('posts')->group(function () {
    Route::post('create-post', [PostController::class, 'create_post']);
    Route::post('get-posts-public', [PostController::class, 'get_posts']);
    Route::post('get-post-by-id', [PostController::class, 'get_postbyid']);
    Route::post('get-posts-private', [PostController::class, 'get_posts_private']);
    Route::post('my-posts', [PostController::class, 'my_posts']);
    Route::post('getvoterlist', [PostController::class, 'getvoterlist']);
    Route::post('vote', [PostController::class, 'vote']);
    Route::post('getNofifications', [PostController::class, 'getNofifications']);
    Route::post('deleteNotification', [PostController::class, 'deleteNotification']);
  
});

Route::prefix('follows')->group(function () {
    Route::post('follow', [PostController::class, 'follow']);
});



Route::prefix('common')->group(function () {
    Route::get('get_terms_and_condition', [CommonController::class, 'get_terms_and_condition']);
    Route::get('plans', [CommonController::class, 'plans']);
    Route::get('get_about_us', [CommonController::class, 'get_about_us']);
    Route::get('get_faqs', [CommonController::class, 'get_faqs']);
    Route::get('get_privacy_policy', [CommonController::class, 'get_privacy_policy']);
    Route::get('get_support', [CommonController::class, 'get_support']);
    Route::post('ask_support', [CommonController::class, 'ask_support']);
    Route::get('get_categories', [CommonController::class, 'get_categories']);
    
    Route::get('get-payment', [CommonController::class, 'get_payment']);
});


Route::prefix('chats')->group(function () {

    Route::post('post_chat', [ChatController::class, 'post_chat']);
    Route::post('get_chat', [ChatController::class, 'get_chat']);
    Route::post('get_last_chats', [ChatController::class, 'get_last_chats']);
    Route::post('update_chat', [ChatController::class, 'update_chat']);
    Route::post('delete_chats', [ChatController::class, 'delete_chats']);
    
});

Route::prefix('reviews')->group(function () {
    
    Route::post('add_ratting', [FavouriteController::class, 'add_ratting']);
    Route::post('delete_favourite', [FavouriteController::class, 'delete_favourite']);
    Route::post('list_ratting', [FavouriteController::class, 'getHotelReviews']);

});
