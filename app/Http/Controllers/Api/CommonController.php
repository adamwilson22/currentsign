<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use DB;
class CommonController extends Controller {
    

    public function get_sub_category_by_category_id(Request $request) {
        
        $category_id = $request->input('category_id');

        $categories = DB::table('sub_categories')->where('sub_cat_category_id',$category_id)->whereNull('sub_cat_deleted_at')->where('sub_cat_admin_status', 'ACTIVE')->get();
        if ($categories) {
            return $this->sendResponse($result = $categories, $message = 'Sub Category Retrieve successfully.', $notification = null, $error = null, $respose_code = 200);
        } else {
            return $this->sendError($result = null, $message = 'Sub Category not found.', $notification = null, $error = null, $respose_code = 200);
        }
    }
    
    public function plans(Request $request) {

        $categories = DB::table('plans')->get();
         
        
        if ($categories) {
            return $this->sendResponse($result = $categories, $message = ' Retrieve successfully.', $notification = null, $error = null, $respose_code = 200);
        } else {
            return $this->sendError($result = null, $message = ' not found.', $notification = null, $error = null, $respose_code = 200);
        }
    }
    
     public function get_categories(Request $request) {

        $categories = DB::table('categories')->whereNull('category_deleted_at')->where('category_admin_status', 'ACTIVE')->get();
        foreach($categories  as &$cat){
            $cat->category_image = url('/').'/public/uploads/categories/'. $cat->category_image;
        } 
        
        if ($categories) {
            return $this->sendResponse($result = $categories, $message = 'Category Retrieve successfully.', $notification = null, $error = null, $respose_code = 200);
        } else {
            return $this->sendError($result = null, $message = 'Category not found.', $notification = null, $error = null, $respose_code = 200);
        }
    }
    
     public function get_payment(Request $request) {

  return $this->sendResponse($result = 'success', $message = 'successfully.', $notification = null, $error = null, $respose_code = 200);

    }
    
    public function get_terms_and_condition(Request $request) {

        $categories = DB::table('terms_and_conditions')->where('tac_admin_status', 'ACTIVE')->get();
        if ($categories) {
            return $this->sendResponse($result = $categories, $message = 'T&C Retrieve successfully.', $notification = null, $error = null, $respose_code = 200);
        } else {
            return $this->sendError($result = null, $message = 'T&C not found.', $notification = null, $error = null, $respose_code = 200);
        }
    }
    
    public function get_about_us(Request $request) {

        $categories = DB::table('about_us')->whereNull('about_us_deleted_at')->where('about_us_admin_status', 'ACTIVE')->get();
        if ($categories) {
            return $this->sendResponse($result = $categories, $message = 'About Us Retrieve successfully.', $notification = null, $error = null, $respose_code = 200);
        } else {
            return $this->sendError($result = null, $message = 'About Us not found.', $notification = null, $error = null, $respose_code = 200);
        }
    }
    
    public function get_faqs(Request $request) {

        $categories = DB::table('faqs')->whereNull('faq_deleted_at')->where('faq_admin_status', 'ACTIVE')->get();
        if ($categories) {
            return $this->sendResponse($result = $categories, $message = 'Faq Retrieve successfully.', $notification = null, $error = null, $respose_code = 200);
        } else {
            return $this->sendError($result = null, $message = 'Faq not found.', $notification = null, $error = null, $respose_code = 200);
        }
    }
    
    public function get_privacy_policy(Request $request) {

        // $categories = DB::table('privacy_policies')->whereNull('pp_deleted_at')->where('pp_admin_status', 'ACTIVE')->get();
                $categories = DB::table('privacy_policies')->where('privacy_policy_admin_status', 'ACTIVE')->get();

        if ($categories) {
            return $this->sendResponse($result = $categories, $message = 'Privacy Policy successfully.', $notification = null, $error = null, $respose_code = 200);
        } else {
            return $this->sendError($result = null, $message = 'Privacy Policy found.', $notification = null, $error = null, $respose_code = 200);
        }
    }
    
    
    public function get_support(Request $request) {

        $categories = DB::table('supports')->whereNull('support_deleted_at')->where('support_admin_status', 'ACTIVE')->get();
        if ($categories) {
            return $this->sendResponse($result = $categories, $message = 'Support Detail successfully.', $notification = null, $error = null, $respose_code = 200);
        } else {
            return $this->sendError($result = null, $message = 'Support detail not found.', $notification = null, $error = null, $respose_code = 200);
        }
    }
    


public function ask_support(Request $request)
{
     if (!Auth::guard('api')->check()) {
            return $this->sendError($result = null, $message = 'Unauthorized.', $notification = null, $error = null, $respose_code = 200);
        }        
        
        $user = Auth::guard('api')->user();
        
        $user_id = $user->id;
        $message = $request->input('message');
        
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'message' => 'required',

    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return $this->sendError(null, $validator->errors()->first(), null, null, 200);
    }

    
    // Insert the support ask into the database
    $support_ask = DB::table('supports')->insertGetId([
        'user_id' => $user_id,
        'message' => $message
        
    ]);

    // Check if insertion was successful
    if ($support_ask) {
        return $this->sendResponse(null, 'Support request submitted successfully.', null, null, 200);
    } else {
        return $this->sendError(null, 'Failed to submit support request.', null, null, 200);
    }
}
    

public function ask_support1(Request $request)
{
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'message' => 'required',
        'support_ask_title' => 'required|string',
        'support_ask_message' => 'required|string',
        'support_ask_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:12048', // Optional image upload validation..
        'support_ask_full_name' => 'required|string',
        'support_ask_mobile_number' => 'required|numeric',
        'support_ask_country_code' => 'required|string',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return $this->sendError(null, $validator->errors()->first(), null, null, 200);
    }

    // Check if the support_id exists
    $support_id = $request->input('support_ask_support_id');
    $support = DB::table('supports')->where('support_id', $support_id)->first();
    if (!$support) {
        return $this->sendError(null, 'Support category not found.', null, null, 200);
    }

    // Handle the image upload if provided
    $image_name = null;
    
    if ($request->hasFile('support_ask_image')) {
        $image = $request->file('support_ask_image');
        $image_extension = $image->getClientOriginalExtension(); // Get the original extension
        $image_name = uniqid() . '_' . time() . '.' . $image_extension; // Generate a unique name with timestamp and original extension
        $support_image = $image->move('public/uploads/support', $image_name); // Store the image with the generated name
    }

    // Insert the support ask into the database
    $support_ask = DB::table('supports_asks')->insertGetId([
        'support_ask_support_id' => $support_id,
        'support_ask_title' => $request->input('support_ask_title'),
        'support_ask_message' => $request->input('support_ask_message'),
        'support_ask_image' => $image_name,
        'support_ask_created_at' => now(),
        'support_ask_updated_at' => now(),
        'support_ask_full_name' => $request->input('support_ask_full_name'),
        'support_ask_mobile_number' => $request->input('support_ask_mobile_number'),
        'support_ask_country_code' => $request->input('support_ask_country_code'),
        
    ]);

    // Check if insertion was successful
    if ($support_ask) {
        return $this->sendResponse(null, 'Support request submitted successfully.', null, null, 200);
    } else {
        return $this->sendError(null, 'Failed to submit support request.', null, null, 200);
    }
}

        
    
    

}
