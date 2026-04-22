<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;

class AuthController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    //($result = [], $message = "" , $notification = [], $error = [] , $respose_code = 200)

    public function signup(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'full_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'country_code' => 'required',
                'mobile_number' => 'required|numeric',
                'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'c_password' => 'required|same:password',

            ],
            [
                'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one number.',
                'password.min' => 'The password must be at least 8 characters long.',
            ]
        );

        // Pre-check if mobile number with country code exists
        $validator->after(function ($validator) use ($request) {
            $countryCode = $request->input('country_code');
            $mobileNumber = $request->input('mobile_number');
            if (
                User::where('country_code', $countryCode)
                    ->where('mobile_number', $mobileNumber)
                    ->exists()
            ) {
                $validator->errors()->add('mobile_number', 'The mobile number with country code already exists.');
            }
        });

        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = [], $message = @$validator_error[0], $notification = [], $error = $validator_error, $respose_code = 200);
        }

        $input = $request->all();

    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/users'), $imageName);
            
            $input['image']  = $imageName;
        }





        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->full_name;

        return $this->sendResponse($result = $success, $message = "Signup Successfully", $notification = [], $error = [], $respose_code = 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required',
            'password' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = null, $message = @$validator_error[0], $notification = [], $error = $validator_error, $respose_code = 400);
        }

        $identity = $request->input('identity');
        $password = $request->input('password');
        $type = $request->input('type');

        // Attempt authentication with email
        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $identity, 'password' => $password, 'user_type' => $type];
        } else {
            // Attempt authentication with mobile number and country code
            $parts = explode('-', $identity);
            if (count($parts) == 2) {
                $countryCode = $parts[0];
                $mobileNumber = $parts[1];
                // Check if there's an associated email
                $user = User::where('country_code', $countryCode)
                    ->where('mobile_number', $mobileNumber)
                    ->first();
                if ($user) {
                    // Use email for authentication
                    $credentials = ['email' => $user->email, 'password' => $password];
                } else {
                    return $this->sendError($result = null, $message = 'Email not found for the provided mobile number.', $notification = [], $error = [], $respose_code = 400);
                }
            } else {
                return $this->sendError($result = null, $message = 'Invalid identity format.', $notification = [], $error = [], $respose_code = 400);
            }
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->profile = FALSE;
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['user_data'] = $user;

            return $this->sendResponse($result = $success, $message = "User login successfully.", $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = null, $message = 'Invalid credentials.', $notification = [], $error = [], $respose_code = 400);
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

        $user = Auth::guard('api')->user();
        

        // Check if the old password matches the user's current password
        if (!Hash::check($request->old_password, $user->password)) {
            return $this->sendError($result = [], $message = 'Invalid old password.', $notification = [], $error = [], $respose_code = 400);
        }

        // Update user's password
        $user->password = bcrypt($request->password);
        $user->save();

        return $this->sendResponse($result = [], $message = 'Password updated successfully.', $notification = [], $error = [], $respose_code = 200);
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
            $credentials = ['email' => $identity];

            $user = User::where('email', $identity)->first();
        } else {
            // Attempt authentication with mobile number and country code
            $parts = explode('-', $identity);
            if (count($parts) == 2) {
                $countryCode = $parts[0];
                $mobileNumber = $parts[1];
                // Check if there's an associated email
                $user = User::where('country_code', $countryCode)
                    ->where('mobile_number', $mobileNumber)
                    ->first();
                if ($user) {
                    // Use email for authentication
                    $credentials = ['email' => $user->email];
                } else {
                    return $this->sendError($result = [], $message = 'Email not found for the provided mobile number.', $notification = [], $error = [], $respose_code = 400);
                }
            } else {
                return $this->sendError($result = [], $message = 'Invalid identity format.', $notification = [], $error = [], $respose_code = 400);
            }
        }

        if(!$user){
                    return $this->sendError($result = [], $message = 'User not found.', $notification = [], $error = [], $respose_code = 400);

        }

        $rand = "9999";
        rand(1111, 9999);

        $user->otp_reset_password = $rand;

        $currentDateTime = date('Y-m-d H:i:s');
        $futureDateTime = date('Y-m-d H:i:s', strtotime($currentDateTime . ' +15 minutes'));

        $user->otp_reset_password_expiration = $futureDateTime;

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
            $credentials = ['email' => $identity];

            $user = User::where('email', $identity)->first();
        } else {
            // Attempt authentication with mobile number and country code
            $parts = explode('-', $identity);
            if (count($parts) == 2) {
                $countryCode = $parts[0];
                $mobileNumber = $parts[1];
                // Check if there's an associated email
                $user = User::where('country_code', $countryCode)
                    ->where('mobile_number', $mobileNumber)
                    ->first();
                if ($user) {
                    // Use email for authentication
                    $credentials = ['email' => $user->email];
                } else {
                    return $this->sendError($result = [], $message = 'Email not found for the provided mobile number.', $notification = [], $error = [], $respose_code = 400);
                }
            } else {
                return $this->sendError($result = [], $message = 'Invalid identity format.', $notification = [], $error = [], $respose_code = 400);
            }
        }

        if ($otp != $user->otp_reset_password) {
            return $this->sendError($result = [], $message = 'Otp not matched.', $notification = [], $error = [], $respose_code = 400);
        }

        $dbTimestamp = strtotime($user->otp_reset_password_expiration);
        $currentTime = time();
        $actualTime = strtotime('-15 minutes', $dbTimestamp);
        $futureTime = $dbTimestamp;

        if ($currentTime >= $actualTime && $currentTime <= $futureTime) {
        } else {
            return $this->sendError($result = [], $message = 'Otp Expired.', $notification = [], $error = [], $respose_code = 400);
        }

        // $user->otp_reset_password = null;

        // $user->otp_reset_password_expiration = null;

        // $user->save();

        return $this->sendResponse($result = [], $message = 'Otp verified successfully.', $notification = [], $error = [], $respose_code = 200);
    }
    
    
    public function createNewPasswordWithoutLogin(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'identity' => 'required',
            'otp' => 'required',
            'password' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = [], $message = @$validator_error[0], $notification = [], $error = $validator_error, $respose_code = 400);
        }




        $identity = $request->input('identity');
        $otp = $request->input('otp');
        $password = $request->input('password');

        // Attempt authentication with email
        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $identity];

            $user = User::where('email', $identity)->first();
        } else {
            // Attempt authentication with mobile number and country code
            $parts = explode('-', $identity);
            if (count($parts) == 2) {
                $countryCode = $parts[0];
                $mobileNumber = $parts[1];
                // Check if there's an associated email
                $user = User::where('country_code', $countryCode)
                    ->where('mobile_number', $mobileNumber)
                    ->first();
                if ($user) {
                    // Use email for authentication
                    $credentials = ['email' => $user->email];
                } else {
                    return $this->sendError($result = [], $message = 'Email not found for the provided mobile number.', $notification = [], $error = [], $respose_code = 400);
                }
            } else {
                return $this->sendError($result = [], $message = 'Invalid identity format.', $notification = [], $error = [], $respose_code = 400);
            }
        }
 
        
         if(!$user) {
                    return $this->sendError($result = [], $message = 'User not found.', $notification = [], $error = [], $respose_code = 400);
                }
        

        if ($otp != $user->otp_reset_password) {
            return $this->sendError($result = [], $message = 'Otp not matched.', $notification = [], $error = [], $respose_code = 400);
        }

        $dbTimestamp = strtotime($user->otp_reset_password_expiration);
        $currentTime = time();
        $actualTime = strtotime('-15 minutes', $dbTimestamp);
        $futureTime = $dbTimestamp;

        if ($currentTime >= $actualTime && $currentTime <= $futureTime) {
        } else {
            return $this->sendError($result = [], $message = 'Otp Expired.', $notification = [], $error = [], $respose_code = 400);
        }

        $user->password = bcrypt($request->password);

        $user->otp_reset_password = null;

        $user->otp_reset_password_expiration = null;

        $user->save();


        return $this->sendResponse($result = [], $message = 'Password updated successfully with otp.', $notification = [], $error = [], $respose_code = 200);
    }
    
    public function getProfile(Request $request)
    {
        
        
        // Authenticating user based on the access token
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 401);
        }        
        
        $user = Auth::guard('api')->user();
        
        //print_r($user);
        
        if($user){
            
              $user->image = url("/") . "/" . "storage/app/users/" .$user->image;
           
            return $this->sendResponse($result = $user, $message = 'Profile retrive successfully.', $notification = [], $error = [], $respose_code = 200);
        }else{
            return $this->sendError($result = [], $message = 'User not found.', $notification = [], $error = [], $respose_code = 200);
        }
        
    }
    
    
    public function updateProfile(Request $request)
{
    // Authenticating user based on the access token
    if (!Auth::guard('api')->check()) {
        return $this->sendError(
            $result = [],
            $message = 'Unauthorized.',
            $notification = [],
            $error = [],
            $respose_code = 401
        );
    }

    $user = Auth::guard('api')->user();


    try {
        
        $data = $request->all();
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('users', 'public'); // Store in the `storage/app/public/users` directory
            $data['image'] = $path;
        }


        // Update the image URL
       $user = User::where('id', $user->id)->update($data);

        return $this->sendResponse(
            $result = $user,
            $message = 'Profile updated successfully.',
            $notification = [],
            $error = [],
            $respose_code = 200
        );
    } catch (\Exception $e) {
        return $this->sendError(
            $result = [],
            $message = 'An error occurred while updating the profile.',
            $notification = [],
            $error = [],
            $respose_code = 500
        );
    }
}

    
    
}
