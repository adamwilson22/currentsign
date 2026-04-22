<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Validator;
use Hash;

class AuthController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    //($result = null, $message = "" , $notification = null, $error = null , $respose_code = 200) 

  public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = null, $message = @$validator_error[0], $notification = null, $error = null, $respose_code = 200);
        }

        $identity = $request->input('identity');
        $password = $request->input('password');
        $type = $request->input('type');

        // Attempt authentication with email
        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $identity, 'password' => $password];
        } else {
            // Attempt authentication with mobile number and country code
            $parts = explode('-', $identity);
            if (count($parts) == 2) {
                $countryCode = $parts[0];
                $mobileNumber = $parts[1];
                // Check if there's an associated email
                $user = User::where('mobile_number', $mobileNumber)->first();
                if ($user) {
                    // Use email for authentication
                    $credentials = ['email' => $user->email, 'password' => $password];
                    // if($user->otp_verify != "TRUE"){
                    //      return $this->sendError($result = null, $message = 'Your Account Not Verified.', $notification = null, $error = null, $respose_code = 200);
                    // }
                    
                } else {
                    return $this->sendError($result = null, $message = 'Email not found for the provided mobile number.', $notification = null, $error = null, $respose_code = 200);
                }
            } else {
                return $this->sendError($result = null, $message = 'Invalid identity format.', $notification = null, $error = null, $respose_code = 200);
            }
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            //  if($user->otp_verify != "TRUE"){
            //              return $this->sendError($result = null, $message = 'Your Account Not Verified.', $notification = null, $error = null, $respose_code = 200);
            //         }
           // $user->profile = FALSE;
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['user_data'] = $user;

            return $this->sendResponse($result = $success, $message = "User login successfully.", $notification = null, $error = null, $respose_code = 200);
        } else {
            return $this->sendError($result = null, $message = 'Invalid credentials.', $notification = null, $error = null, $respose_code = 200);
        }
    }







      public function getProfile(Request $request)
    {
        // Authenticating user based on the access token
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = null, $message = 'Unauthorized.', $notification = null, $error = null, $respose_code = 200);
        }        
        
        $user = Auth::guard('api')->user();
        
        //print_r($user);
        
        if($user){
            if($user->image){
                 $user->image = url("/") . "/" . "storage/app/public/" .$user->image;
            }
            
            $followersCount = DB::table('follows')
            ->where('following_id', $user->id)
            ->where('status', 'accepted')
            ->count();
             
            $user->followers_count = $followersCount;
             
            $followingCount = DB::table('follows')
            ->where('follower_id', $user->id)
            ->where('status', 'accepted')
            ->count();
            
            $user->following_count = $followingCount;
            
            /*--------------------------------signatures----------------------------------------------*/
                 $AwaitingCount = DB::table('signatures')
            ->where('user_id', $user->id)
            ->where('status', 'Awaiting')
            ->count();
             
            $user->AwaitingCount = $AwaitingCount;
             
            $signedCount = DB::table('signatures')
            ->where('user_id', $user->id)
            ->where('status', 'signed')
            ->count();
            
            $user->signedCount = $signedCount;
            
            
             
              $success['token'] = '';
              $success['user_data'] = $user; 
           
            return $this->sendResponse($result = $success, $message = 'Profile retrive successfully.', $notification = null, $error = null, $respose_code = 200);
        }else{
            return $this->sendError($result = null, $message = 'User not found.', $notification = null, $error = null, $respose_code = 200);
        }
        
    }
    
    
    
    


/*---------------------------------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------------------------------------------------------------------------------------------------------*/








    public function signup(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'dob' => 'required',
                'full_name' => 'required',
                'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
            [
                'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one number.',
                'password.min' => 'The password must be at least 8 characters long.',
            ]
        );
    
        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = null, $message = @$validator_error[0], $notification = null, $error = null, $respose_code = 200);
        }
      
            $email = $request->input('email');
            $countryCode = $request->input('country_code');
            $mobileNumber = $request->input('mobile_number');

        // Pre-check if mobile number with country code exists
        $validator->after(function ($validator) use ($request) {
        //      $countryCode = $request->input('country_code');
        //      $mobileNumber = $request->input('mobile_number');
             
              $email = $request->input('email');
             
        //      // profile_status  TRUE
        //   //   otp_verify TRUE
            
        //     if (
        //         User::where('country_code', $countryCode)
        //             ->where('mobile_number', $mobileNumber)
        //             ->exists()
        //     ) {
                
        //         $validator->errors()->add('mobile_number', 'The mobile number with country code already exists.');
        //     }
            
            
             if (
                User::where('email', $email)
                    ->exists()
            ) {
                
                $validator->errors()->add('email', 'The email already exists.');
            }
            
        });

        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = null, $message = @$validator_error[0], $notification = null, $error = null, $respose_code = 200);
        }

        $input = $request->all();
        

    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/users'), $imageName);
            
            $input['image']  = $imageName;
        }
       
       
       $countryCode = $request->input('country_code');
            $mobileNumber = $request->input('mobile_number');

        
        $u = User::where('email', $input['email'])->first();
       
        if(!$u){
       $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $dd= User::find($user->id);
        $rand = "9999";
        rand(1111, 9999);

        $dd->otp = $rand;

        $currentDateTime = date('Y-m-d H:i:s');
        $futureDateTime = date('Y-m-d H:i:s', strtotime($currentDateTime . ' +15 minutes'));

        $dd->otp_time = $futureDateTime;

        $dd->save();
        
        $success['token'] ='' ;//$user->createToken('MyApp')->accessToken;
        $success['user_data'] = $dd;
        }else{
        $input['password'] = bcrypt($input['password']);
        $rand = "9999";
        rand(1111, 9999);

          $input['otp'] = $rand;
          $currentDateTime = date('Y-m-d H:i:s');
          $futureDateTime = date('Y-m-d H:i:s', strtotime($currentDateTime . ' +15 minutes'));
          $input['otp_time'] = $futureDateTime;
          User::where('email', $input['email'])->update($input);
          $user =  User::where('email', $input['email'])->first();
           $user->update($input);
          $success['token'] = $user->createToken('MyApp')->accessToken;
          $success['user_data'] = $user;
            
        }

        

        return $this->sendResponse($result = $success, $message = "Signup Successfully", $notification = null, $error = null, $respose_code = 200);
    }

  
    public function createNewPassword(Request $request)
    {
        $user_id = $request->input('user_id'); 
        // Authenticating user based on the access token
        // if (!Auth::guard('api')->check()) {
        //     return $this->sendError($result = null, $message = 'Unauthorized.', $notification = null, $error = null, $respose_code = 200);
        // }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'password' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = null, $message = @$validator_error[0], $notification = null, $error = null, $respose_code = 200);
        }

        // $user = Auth::guard('api')->user();
        

        // Check if the old password matches the user's current password
        // if (!Hash::check($request->old_password, $user->password)) {
        //     return $this->sendError($result = null, $message = 'Invalid old password.', $notification = null, $error = null, $respose_code = 200);
        // }

        // Update user's password
        // $user->password = bcrypt($request->password);
        // $user->save();
        
        $hashedPassword = bcrypt($request->password);

    // Update user's password in the database using DB facade
        DB::table('users')
        ->where('id', $user_id) // Assuming you are passing user_id
        ->update(['password' => $hashedPassword]);

        return $this->sendResponse($result = null, $message = 'Password updated successfully.', $notification = null, $error = null, $respose_code = 200);
    }
    
    
     public function changePassword(Request $request)
    {
        // Authenticating user based on the access token
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = null, $message = 'Unauthorized.', $notification = null, $error = null, $respose_code = 200);
        }

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = null, $message = @$validator_error[0], $notification = null, $error = null, $respose_code = 200);
        }

        $user = Auth::guard('api')->user();
        

        // Check if the old password matches the user's current password
        if (!Hash::check($request->old_password, $user->password)) {
            return $this->sendError($result = null, $message = 'Invalid old password.', $notification = null, $error = null, $respose_code = 200);
        }

        // Update user's password
        $user->password = bcrypt($request->password);
        $user->save();

        return $this->sendResponse($result = null, $message = 'Password updated successfully.', $notification = null, $error = null, $respose_code = 200);
    }


    
    

    public function passwordReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required',
        ]);

        if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = null, $message = @$validator_error[0], $notification = null, $error = null, $respose_code = 200);
        }

        $identity = $request->input('identity'); 

        // Attempt authentication with email
        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $identity];

            $user = User::where('email', $identity)->first();
            
            
            
        } else {
            
          //  echo 'sd';die;
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
                    return $this->sendError($result = null, $message = 'Email not found for the provided mobile number.', $notification = null, $error = null, $respose_code = 200);
                }
            } else {
                return $this->sendError($result = null, $message = 'Invalid identity format.', $notification = null, $error = null, $respose_code = 200);
            }
        }


        if(!$user){
                    return $this->sendError($result = null, $message = 'User not found.', $notification = null, $error = null, $respose_code = 200);

        }

        $rand = "9999";
        rand(1111, 9999);

        $user->otp = $rand;

        $currentDateTime = date('Y-m-d H:i:s');
        $futureDateTime = date('Y-m-d H:i:s', strtotime($currentDateTime . ' +15 minutes'));

        $user->otp_time = $futureDateTime;

        $user->save();

        return $this->sendResponse($result = null, $message = 'Otp send successfully.', $notification = null, $error = null, $respose_code = 200);
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
            return $this->sendError($result = null, $message = @$validator_error[0], $notification = null, $error = null, $respose_code = 200);
        }

        $identity = $request->input('identity');
        $otp = $request->input('otp');

        // Attempt authentication with email
        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $identity];

            $user = User::where('email', $identity)->first();
            if(!$user){
                return $this->sendError($result = null, $message = 'Email not found for the provided mobile number.', $notification = null, $error = null, $respose_code = 200);
            }
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
                    return $this->sendError($result = null, $message = 'Email not found for the provided mobile number.', $notification = null, $error = null, $respose_code = 200);
                }
            } else {
                return $this->sendError($result = null, $message = 'Invalid identity format.', $notification = null, $error = null, $respose_code = 200);
            }
        }

        if ($otp != $user->otp) {
            $result1 = [
        'user_id' => $user->id,
        // Add other result data if necessary
    ];
    
            return $this->sendError($result = null, $message = 'Otp not matched.', $notification = null, $error = null, $respose_code = 200);
        }

        $dbTimestamp = strtotime($user->otp_time);
        $currentTime = time();
        $actualTime = strtotime('-15 minutes', $dbTimestamp);
        $futureTime = $dbTimestamp;

        if ($currentTime >= $actualTime && $currentTime <= $futureTime) {
        } else {
           
         $result1 = [
        'user_id' => $user->id,
        // Add other result data if necessary
    ];
            return $this->sendError($result = null, $message = 'Otp Expired.', $notification = null, $error = null, $respose_code = 200);
        }

        $user->otp = null;

        $user->otp_time = null;
        
        $user->otp_verify = "TRUE";

        $user->save();
        
        
          $success['token'] = $user->createToken('MyApp')->accessToken;
          $success['user_data'] = $user;
          
        return $this->sendResponse($result = $success, $message = 'Otp verified successfully.', $notification = null, $error = null, $respose_code = 200);
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
            return $this->sendError($result = null, $message = @$validator_error[0], $notification = null, $error = null, $respose_code = 200);
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
                    return $this->sendError($result = null, $message = 'Email not found for the provided mobile number.', $notification = null, $error = null, $respose_code = 200);
                }
            } else {
                return $this->sendError($result = null, $message = 'Invalid identity format.', $notification = null, $error = null, $respose_code = 200);
            }
        }
 
        
         if(!$user) {
                    return $this->sendError($result = null, $message = 'User not found.', $notification = null, $error = null, $respose_code = 200);
                }
        

        if ($otp != $user->otp_reset_password) {
            return $this->sendError($result = null, $message = 'Otp not matched.', $notification = null, $error = null, $respose_code = 200);
        }

        $dbTimestamp = strtotime($user->otp_reset_password_expiration);
        $currentTime = time();
        $actualTime = strtotime('-15 minutes', $dbTimestamp);
        $futureTime = $dbTimestamp;

        if ($currentTime >= $actualTime && $currentTime <= $futureTime) {
        } else {
            return $this->sendError($result = null, $message = 'Otp Expired.', $notification = null, $error = null, $respose_code = 200);
        }

        $user->password = bcrypt($request->password);

        $user->otp_reset_password = null;

        $user->otp_reset_password_expiration = null;

        $user->save();


        return $this->sendResponse($result = null, $message = 'Password updated successfully with otp.', $notification = null, $error = null, $respose_code = 200);
    }
    
        public function updateProfile(Request $request)
{
    // Authenticating user based on the access token
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


    try {
        
        $data = $request->all();
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('users', 'public'); // Store in the `storage/app/public/users` directory
            $data['image'] = $path;
        }


        // Update the image URL
        User::where('id', $user->id)->update($data);
        $user =  User::where('id', $user->id)->first();
        if($user->image){
            $user->image = url('/').'/storage/app/public/'.$user->image;
        }
        
         $success['token'] = '';
         $success['user_data'] = $user;

        return $this->sendResponse(
            $result = $success,
            $message = 'Profile updated successfully.',
            $notification = [],
            $error = [],
            $respose_code = 200
        );
    } catch (\Exception $e) {
        return $this->sendError(
            $result = null,
            $message = 'An error occurred while updating the profile.',
            $notification = [],
            $error = [],
            $respose_code = 500
        );
    }
}

public function delete_account(Request $request){
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
    DB::table('users')->where('id', $user->id)->delete();
    DB::table('posts')->where('user_id', $user->id)->delete();
    return $this->sendResponse(
            $result = null,
            $message = 'successfully.',
            $notification = [],
            $error = [],
            $respose_code = 200
        );
}


public function getUsers(){
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
    $users = User::Where('id', '!=', $user->id)->get();
    foreach($users as &$u){
        if($u->image){
            $u->image = url('/').'/storage/app/public/'.$u->image;
        }
                $isFollowing = DB::table('follows')
                    ->where('follower_id', $user->id)
                    ->where('following_id', $u->id)
                    ->where('status', 'accepted')
                    ->exists();

                // Check if this user is following the authenticated user
                $isFollowedBy = DB::table('follows')
                    ->where('follower_id', $u->id)
                    ->where('following_id', $user->id)
                    ->where('status', 'accepted')
                    ->exists();
                    
               $u->status = $isFollowing ? 'Following' : ($isFollowedBy ? 'Follow Back' : 'Follow');
    }
    
     return $this->sendResponse(
            $result = $users,
            $message = 'Users Retrive successfully.',
            $notification = [],
            $error = [],
            $respose_code = 200
        );
}
    
  
    
    
}
