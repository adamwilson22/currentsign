<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserRestaurant;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Validator;
use Hash;

//($result = [], $message = "" , $notification = [], $error = [] , $respose_code = 200)

class RestaurantController extends Controller
{
    public function getRestaurantById(Request $request)
    {
        // Authenticating user based on the access token
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 401);
        }

        $restaurant_id = $request->input('restaurant_id');

        $restaurants = DB::select("SELECT * FROM `restaurants` where `res_id` = '$restaurant_id' and  `res_deleted_at` IS NULL and `res_admin_status` = 'ACTIVE' order by res_id desc");

        if (is_array($restaurants) && !empty($restaurants)) {
            foreach ($restaurants as $restaurantsIn) {
                $restaurantsIn->res_image = url("/") . "/storage/app/restaurants/" . $restaurantsIn->res_image;
            }

            $result['restaurant'] = @$restaurants[0];

            /*****************************************/

            $get_categories = DB::select("SELECT `reit_categories_id` FROM `restaurants_items` WHERE `reit_restaurants_id`='1' and `reit_admin_status` = 'ACTIVE' and `reit_deleted_at` IS NULL");

            $categorySelect = [];

            if (is_array($get_categories)) {
                foreach ($get_categories as $get_categoriesIn) {
                    $categorySelect[] = $get_categoriesIn->reit_categories_id;
                }
            }

            $categorySelectImplode = "'" . implode("','", $categorySelect) . "'";

            /*****************************************/

            $get_categories_of_restaurant = DB::select("SELECT * FROM `restaurants_categories` WHERE `rescat_id` IN ($categorySelectImplode) and `rescat_deleted_at` IS NULL  and `rescat_admin_status` = 'ACTIVE'");

            //print_r($get_categories_of_restaurant);

            foreach ($get_categories_of_restaurant as $get_categories_of_restaurantIn) {
                $get_categories_of_restaurantIn->rescat_image = url("/") . "/storage/app/restaurants/" . $get_categories_of_restaurantIn->rescat_image;

                $fetch_items = [];

                $restaurants_items = DB::select(
                    "SELECT * FROM `restaurants_items` where `reit_id` = '" .
                        $get_categories_of_restaurantIn->rescat_id .
                        "' and `reit_restaurants_id` = '$restaurant_id' and `reit_deleted_at` IS NULL and `reit_admin_status` = 'ACTIVE' order by reit_id desc limit 10"
                );

                if (is_array($restaurants_items) && !empty($restaurants_items)) {
                    foreach ($restaurants_items as $restaurants_itemsIn) {
                        $restaurants_itemsIn->reit_image = url("/") . "/storage/app/restaurants-items/" . $restaurants_itemsIn->reit_image;

                        $fetch_items[] = $restaurants_itemsIn;
                    }
                }

                $get_categories_of_restaurantIn->category_items = $fetch_items;
            }

            /*****************************************/

            /*****************************************/

            $result['restaurant_items_category_wise'] = @$get_categories_of_restaurant;

            /*****************************************/

            $restaurants_items = DB::select("SELECT * FROM `restaurants_items` where  `reit_restaurants_id` = '$restaurant_id' and `reit_deleted_at` IS NULL and `reit_admin_status` = 'ACTIVE' order by reit_id desc limit 10");

            if (is_array($restaurants_items) && !empty($restaurants_items)) {
                foreach ($restaurants_items as $restaurants_itemsIn) {
                    $restaurants_itemsIn->reit_image = url("/") . "/storage/app/restaurants-items/" . $restaurants_itemsIn->reit_image;
                }
            }

            $result['popular_items'] = @$restaurants_items;

            /*****************************************/

            $restaurants_reviews = DB::select(
                "SELECT `restaurants_reviews`.*, `users`.`full_name`,`users`.`images` FROM `restaurants_reviews` left JOIN `users` ON `restaurants_reviews`.`resrev_user_id` = `users`.`id` WHERE `resrev_restaurants_id` = '" .
                    $restaurant_id .
                    "' order by `resrev_created_at` desc limit 10;"
            );

            if (is_array($restaurants_reviews) && !empty($restaurants_reviews)) {
                foreach ($restaurants_reviews as $restaurants_reviewsIn) {
                    $restaurants_reviewsIn->images = url("/") . "/storage/app/users/" . $restaurants_reviewsIn->images;
                }
            }

            $result['review'] = @$restaurants_reviews;

            /*****************************************/

            return $this->sendResponse($result = $result, $message = 'Restaurant data.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Restaurant data not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }

    public function getItemsByRestaurantId(Request $request)
    {
        // Authenticating user based on the access token
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 401);
        }

        $restaurant_id = $request->input('restaurant_id');

        $restaurants = DB::select("SELECT * FROM `restaurants` where `res_id` = '$restaurant_id' and  `res_deleted_at` IS NULL and `res_admin_status` = 'ACTIVE' order by res_id desc");

        if (is_array($restaurants) && !empty($restaurants)) {
            foreach ($restaurants as $restaurantsIn) {
                $restaurantsIn->res_image = url("/") . "/storage/app/restaurants/" . $restaurantsIn->res_image;
            }

            $result['restaurant'] = @$restaurants[0];
            return $this->sendResponse($result = $result, $message = 'Restaurant data.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Restaurant data not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }


    /*
    public function signup(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'useres_full_name' => 'required',
                'useres_email' => 'required|email|unique:users_restaurants,useres_email',
                'useres_country_code' => 'required',
                'useres_mobile_number' => 'required|numeric',
                'useres_password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'useres_c_password' => 'required|same:useres_password',
            ],
            [
                'useres_password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one number.',
                'useres_password.min' => 'The password must be at least 8 characters long.',
            ]
        );

        // Pre-check if mobile number with country code exists
        $validator->after(function ($validator) use ($request) {
            $countryCode = $request->input('useres_country_code');
            $mobileNumber = $request->input('useres_mobile_number');

            if (
                UserRestaurant::where('useres_country_code', $countryCode)
                    ->where('useres_mobile_number', $mobileNumber)
                    ->exists()
            ) {
                $validator->errors()->add('useres_mobile_number', 'The mobile number with country code already exists.');
            }
        });

        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = [], $message = @$validator_error[0], $notification = [], $error = $validator_error, $respose_code = 200);
        }

        $input = $request->all();
        
        
        $input['useres_password'] = bcrypt($input['useres_password']);
        
        
        $user = UserRestaurant::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->useres_full_name;

        return $this->sendResponse($result = $success, $message = "Signup Successfully", $notification = [], $error = [], $respose_code = 200);
    }
    
    */
    
    
        public function signup(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'useres_full_name' => 'required',
                'useres_email' => 'required|email|unique:users_restaurants,useres_email',
                'useres_country_code' => 'required',
                'useres_mobile_number' => 'required|numeric',
                'useres_password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'useres_c_password' => 'required|same:useres_password',
            ],
            [
                'useres_password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one number.',
                'useres_password.min' => 'The password must be at least 8 characters long.',
            ]
        );

        // Pre-check if mobile number with country code exists
        $validator->after(function ($validator) use ($request) {
            $countryCode = $request->input('useres_country_code');
            $mobileNumber = $request->input('useres_mobile_number');
            if (
                UserRestaurant::where('useres_country_code', $countryCode)
                    ->where('useres_mobile_number', $mobileNumber)
                    ->exists()
            ) {
                $validator->errors()->add('useres_mobile_number', 'The mobile number with country code already exists.');
            }
        });

        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = [], $message = @$validator_error[0], $notification = [], $error = $validator_error, $respose_code = 200);
        }

        $input = $request->all();
        
        echo $haspassword =  Hash::make($input['useres_password']);
        
        
        
          (Hash::check($input['useres_password'], $haspassword )) ;


         $input['useres_password'] = $haspassword;
        
        print_r($input);
        
        $user = UserRestaurant::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->useres_full_name;

        return $this->sendResponse($result = $success, $message = "Signup Successfully", $notification = [], $error = [], $respose_code = 200);
    }
    

    public function login(Request $request)
    {

        
        
        $validator = Validator::make($request->all(), [
            'useres_identity' => 'required',
            'useres_password' => 'required',
        ]);

        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = [], $message = @$validator_error[0], $notification = [], $error = $validator_error, $respose_code = 400);
        }

        
        $identity = $request->input('useres_identity');
        $password = $request->input('useres_password');
        

        // Attempt authentication with email
        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['useres_email' => $identity, 'password' => $password];
        } else {
            // Attempt authentication with mobile number and country code
            $parts = explode('-', $identity);
            if (count($parts) == 2) {
                $countryCode = $parts[0];
                $mobileNumber = $parts[1];
                // Check if there's an associated email
                $user = UserRestaurant::where('useres_country_code', $countryCode)
                    ->where('useres_mobile_number', $mobileNumber)
                    ->first();

                if ($user) {
                    // Use email for authentication
                    $credentials = ['useres_email' => $user->useres_email, 'password' => $password];
                } else {
                    return $this->sendError($result = [], $message = 'Email not found for the provided mobile number.', $notification = [], $error = [], $respose_code = 400);
                }
            } else {
                return $this->sendError($result = [], $message = 'Invalid identity format.', $notification = [], $error = [], $respose_code = 400);
            }
        }



        $user = UserRestaurant::where('useres_email', $identity)
            ->first();
            
        $user = UserRestaurant::find(1);
        
        Auth::guard('restaurant')->login($user);
        
        
           
           print_r($credentials);

        echo Auth::guard('restaurant')->attempt($credentials);
        
        die;
        
        if ("11"=="11") {
            $user = Auth::guard('restaurant')->user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['user_data'] = $user;

            return $this->sendResponse($result = $success, $message = "User login successfully.", $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Invalid credentials.', $notification = [], $error = [], $respose_code = 400);
        }
    }

    public function createNewPassword(Request $request)
    {
        // Authenticating user based on the access token
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 401);
        }

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = [], $message = @$validator_error[0], $notification = [], $error = $validator_error, $respose_code = 400);
        }

        $user = Auth::user();

        // Check if the old password matches the user's current password
        if (!Hash::check($request->old_password, $user->password)) {
            return $this->sendError($result = [], $message = 'Invalid old password.', $notification = [], $error = [], $respose_code = 400);
        }

        // Update user's password
        $user->password = bcrypt($request->password);
        $user->save();

        // Generate a new token for the user
        $token = $user->createToken('MyApp')->accessToken;

        return $this->sendResponse($result = ['token' => $token], $message = 'Password updated successfully.', $notification = [], $error = [], $respose_code = 200);
    }

    public function passwordReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required',
        ]);

        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = [], $message = @$validator_error[0], $notification = [], $error = $validator_error, $respose_code = 400);
        }

        $identity = $request->input('identity');

        // Attempt authentication with email
        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            $user = UserRestaurant::where('useres_email', $identity)->first();
            if (!$user) {
                return $this->sendError($result = [], $message = 'User not found with this email.', $notification = [], $error = [], $respose_code = 400);
            }
        } else {
            // Attempt authentication with mobile number and country code
            $parts = explode('-', $identity);
            if (count($parts) == 2) {
                $countryCode = $parts[0];
                $mobileNumber = $parts[1];
                // Check if there's an associated email
                $user = UserRestaurant::where('useres_country_code', $countryCode)
                    ->where('useres_mobile_number', $mobileNumber)
                    ->first();
                if ($user) {
                    // Use email for authentication
                    $identity = $user->useres_email;
                } else {
                    return $this->sendError($result = [], $message = 'Email not found for the provided mobile number.', $notification = [], $error = [], $respose_code = 400);
                }
            } else {
                return $this->sendError($result = [], $message = 'Invalid identity format.', $notification = [], $error = [], $respose_code = 400);
            }
        }

        $rand = "9999";
        $rand = rand(1111, 9999);

        $user->useres_otp_reset_password = $rand;

        $currentDateTime = date('Y-m-d H:i:s');
        $futureDateTime = date('Y-m-d H:i:s', strtotime($currentDateTime . ' +15 minutes'));

        $user->useres_otp_reset_password_expiration = $futureDateTime;

        $user->save();

        return $this->sendResponse($result = [], $message = 'Otp send successfully.', $notification = [], $error = [], $respose_code = 200);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = [], $message = @$validator_error[0], $notification = [], $error = $validator_error, $respose_code = 400);
        }

        $identity = $request->input('identity');
        $otp = $request->input('otp');

        // Attempt authentication with email
        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['useres_email' => $identity];

            $user = UserRestaurant::where('useres_email', $identity)->first();
        } else {
            // Attempt authentication with mobile number and country code
            $parts = explode('-', $identity);
            if (count($parts) == 2) {
                $countryCode = $parts[0];
                $mobileNumber = $parts[1];
                // Check if there's an associated email
                $user = UserRestaurant::where('useres_country_code', $countryCode)
                    ->where('useres_mobile_number', $mobileNumber)
                    ->first();
                if ($user) {
                    // Use email for authentication
                    $credentials = ['useres_email' => $user->useres_email];
                } else {
                    return $this->sendError($result = [], $message = 'Email not found for the provided mobile number.', $notification = [], $error = [], $respose_code = 400);
                }
            } else {
                return $this->sendError($result = [], $message = 'Invalid identity format.', $notification = [], $error = [], $respose_code = 400);
            }
        }

        if ($otp != $user->useres_otp_reset_password) {
            return $this->sendError($result = [], $message = 'Otp not matched.', $notification = [], $error = [], $respose_code = 400);
        }

        $dbTimestamp = strtotime($user->useres_otp_reset_password_expiration);
        $currentTime = time();
        $actualTime = strtotime('-15 minutes', $dbTimestamp);
        $futureTime = $dbTimestamp;

        if ($currentTime >= $actualTime && $currentTime <= $futureTime) {
        } else {
            return $this->sendError($result = [], $message = 'Otp Expired.', $notification = [], $error = [], $respose_code = 400);
        }

        $user->useres_otp_reset_password = null;

        $user->useres_otp_reset_password_expiration = null;

        $user->save();

        return $this->sendResponse($result = [], $message = 'Otp verified successfully.', $notification = [], $error = [], $respose_code = 200);
    }

    public function getProfile(Request $request)
    {
        // Authenticating user based on the access token
        if (!Auth::guard('restaurant-api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 401);
        }

        $user = Auth::guard('restaurant-api')->user();

        if ($user) {
            $user->images = url("/") . "/" . "storage/app/users/" . $user->images;

            return $this->sendResponse($result = $user, $message = 'Profile retrive successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'User not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
}
