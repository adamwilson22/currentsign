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
            try {
                $success['token'] = $this->issueApiToken($user);
                $success['user_data'] = $user;
                return $this->sendResponse($result = $success, $message = "User login successfully.", $notification = null, $error = null, $respose_code = 200);
            } catch (\Throwable $e) {
                \Log::error('login token failed', ['error' => $e->getMessage()]);
                return $this->sendError(
                    null,
                    'Login succeeded but API token could not be created: ' . $e->getMessage(),
                    null,
                    null,
                    200
                );
            }
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
        
        if($user){
            try {
                $absolute = $this->absoluteUserImageUrl($user->image);
                $user->image = $absolute;
                $user->image_url = $absolute;

                $user->followers_count = 0;
                $user->following_count = 0;
                if (\Illuminate\Support\Facades\Schema::hasTable('follows')) {
                    $user->followers_count = DB::table('follows')
                        ->where('following_id', $user->id)
                        ->where('status', 'accepted')
                        ->count();
                    $user->following_count = DB::table('follows')
                        ->where('follower_id', $user->id)
                        ->where('status', 'accepted')
                        ->count();
                }

                $user->AwaitingCount = 0;
                $user->signedCount = 0;
                if (\Illuminate\Support\Facades\Schema::hasTable('signatures')) {
                    $user->AwaitingCount = DB::table('signatures')
                        ->where('user_id', $user->id)
                        ->whereRaw('LOWER(status) = ?', ['awaiting'])
                        ->count();
                    $user->signedCount = DB::table('signatures')
                        ->where('user_id', $user->id)
                        ->whereRaw('LOWER(status) = ?', ['signed'])
                        ->count();
                }

                $user->is_trial = $user->is_trial ?? 'true';
                $user->plan = $user->plan ?? ($user->is_trial === 'false' ? 'expired_trial' : 'trial');
                $user->subscription_status = $user->subscription_status ?? ($user->is_trial === 'false' ? 'expired' : 'trialing');

                $success['token'] = '';
                $success['user_data'] = $user; 
               
                return $this->sendResponse($result = $success, $message = 'Profile retrive successfully.', $notification = null, $error = null, $respose_code = 200);
            } catch (\Throwable $e) {
                \Log::error('getProfile failed', ['error' => $e->getMessage()]);
                // Still return the authenticated user so mobile Settings/Dashboard can load.
                $success['token'] = '';
                $success['user_data'] = $user;
                return $this->sendResponse($result = $success, $message = 'Profile retrive successfully.', $notification = null, $error = null, $respose_code = 200);
            }
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
        // Align with website registration: full_name, email, password (min 6). dob optional.
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'dob' => 'nullable|string',
                'full_name' => 'required|string|max:255',
                'password' => 'required|min:6',
            ],
            [
                'password.min' => 'The password must be at least 6 characters long.',
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
       
        try {
        if(!$u){
        $payload = $this->filterUserColumns([
            'name' => $request->input('full_name'),
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            // Plain password — User model casts `password` => hashed
            'password' => $request->input('password'),
            'dob' => $request->input('dob') ?: '2000-01-01',
            'country_code' => $request->input('country_code'),
            'mobile_number' => $request->input('mobile_number'),
            'image' => $input['image'] ?? null,
            'is_trial' => $request->input('is_trial', 'true'),
            'otp_verify' => 'TRUE',
        ]);
        $user = User::create($payload);
        $dd= User::find($user->id);
        $rand = "9999";
        rand(1111, 9999);

        $otpPatch = $this->filterUserColumns([
            'otp' => $rand,
            'otp_time' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +15 minutes')),
        ]);
        if (!empty($otpPatch)) {
            $dd->fill($otpPatch)->save();
        }
        
        $success['token'] = $this->issueApiToken($dd);
        $success['user_data'] = $dd;
        }else{
        $payload = $this->filterUserColumns([
            'password' => $request->input('password'),
            'full_name' => $request->input('full_name'),
            'name' => $request->input('full_name'),
            'dob' => $request->input('dob') ?: '2000-01-01',
            'otp' => '9999',
            'otp_time' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +15 minutes')),
        ]);
          User::where('email', $input['email'])->update($payload);
          $user =  User::where('email', $input['email'])->first();
          $success['token'] = $this->issueApiToken($user);
          $success['user_data'] = $user;
            
        }
        } catch (\Throwable $e) {
            \Log::error('signup failed', ['error' => $e->getMessage()]);
            return $this->sendError(
                null,
                'Unable to create account: ' . $e->getMessage(),
                null,
                null,
                200
            );
        }

        

        return $this->sendResponse($result = $success, $message = "Signup Successfully", $notification = null, $error = null, $respose_code = 200);
    }

    public function logout(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError(null, 'Unauthorized.', null, null, 401);
        }

        $user = Auth::guard('api')->user();
        $token = $user->token();
        if ($token) {
            $token->revoke();
        }

        return $this->sendResponse(null, 'Logged out successfully.', null, null, 200);
    }

    /**
     * Social login (Apple / Google). Verifies identity client-side token payload lightly;
     * production should verify JWT with Apple/Google JWKS.
     */
    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'required|in:apple,google,facebook',
            'id_token' => 'required|string',
            'email' => 'nullable|email',
            'full_name' => 'nullable|string|max:255',
            'nonce' => 'nullable|string',
            'provider_user_id' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors()->first(), null, null, 422);
        }

        $provider = $request->provider;
        $providerUserId = $request->provider_user_id;
        $email = $request->email;

        // Decode JWT payload (no signature verify here — add JWKS verify in production).
        $parts = explode('.', $request->id_token);
        if (count($parts) >= 2) {
            $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
            if (is_array($payload)) {
                $providerUserId = $providerUserId ?: ($payload['sub'] ?? null);
                $email = $email ?: ($payload['email'] ?? null);
            }
        }

        if (! $providerUserId) {
            return $this->sendError(null, 'Unable to resolve provider user id.', null, null, 422);
        }

        $user = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('social_accounts')) {
            $link = DB::table('social_accounts')
                ->where('provider', $provider)
                ->where('provider_user_id', $providerUserId)
                ->first();
            if ($link) {
                $user = User::find($link->user_id);
            }
        }

        if (! $user && $email) {
            $user = User::where('email', $email)->first();
        }

        if (! $user) {
            $user = User::create([
                'full_name' => $request->full_name ?: ($email ? explode('@', $email)[0] : 'CurrentSign User'),
                'email' => $email ?: ($provider . '_' . $providerUserId . '@privaterelay.currentsign.local'),
                'password' => Hash::make(bin2hex(random_bytes(16))),
                'dob' => $request->input('dob') ?: '2000-01-01',
                'is_trial' => 'true',
                'otp_verify' => 'TRUE',
            ]);
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('social_accounts')) {
            DB::table('social_accounts')->updateOrInsert(
                ['provider' => $provider, 'provider_user_id' => $providerUserId],
                [
                    'user_id' => $user->id,
                    'email' => $email,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $success = [
            'token' => $this->issueApiToken($user),
            'user_data' => $user,
        ];

        return $this->sendResponse($success, 'Social login successful.');
    }

    public function socialLink(Request $request)
    {
        if (! Auth::guard('api')->check()) {
            return $this->sendError(null, 'Unauthorized.', null, null, 401);
        }
        return $this->socialLogin($request);
    }

    public function socialUnlink(Request $request)
    {
        if (! Auth::guard('api')->check()) {
            return $this->sendError(null, 'Unauthorized.', null, null, 401);
        }
        $provider = $request->input('provider');
        if (! $provider) {
            return $this->sendError(null, 'provider is required.', null, null, 422);
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('social_accounts')) {
            DB::table('social_accounts')
                ->where('user_id', Auth::guard('api')->id())
                ->where('provider', $provider)
                ->delete();
        }
        return $this->sendResponse(null, 'Social account unlinked.');
    }

    public function linkedSocial(Request $request)
    {
        if (! Auth::guard('api')->check()) {
            return $this->sendError(null, 'Unauthorized.', null, null, 401);
        }
        $rows = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('social_accounts')) {
            $rows = DB::table('social_accounts')
                ->where('user_id', Auth::guard('api')->id())
                ->pluck('provider')
                ->values()
                ->all();
        }
        return $this->sendResponse(['providers' => $rows], 'Linked providers.');
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


    
    

    /** Resolve user by email or country_code-mobile identity. */
    private function findUserByIdentity(?string $identity)
    {
        if ($identity === null || trim($identity) === '') {
            return null;
        }
        $identity = trim($identity);
        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            return User::where('email', $identity)->first();
        }
        $parts = explode('-', $identity);
        if (count($parts) == 2) {
            return User::where('country_code', $parts[0])
                ->where('mobile_number', $parts[1])
                ->first();
        }
        return null;
    }

    private function absoluteUserImageUrl(?string $image): ?string
    {
        if ($image === null || $image === '') {
            return null;
        }
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            // Always prefer https for mobile NetworkImage (cleartext blocked).
            $url = preg_replace('#^http://#i', 'https://', $image);
            // Hosting serves user avatars under /public/uploads/users/...
            $url = str_replace('/uploads/users/', '/public/uploads/users/', $url);
            return $url;
        }
        $path = ltrim($image, '/');
        if (str_starts_with($path, 'users/')) {
            $path = 'storage/' . $path;
        } elseif (str_starts_with($path, 'uploads/users/')) {
            $path = 'public/' . $path;
        } elseif (! str_starts_with($path, 'public/uploads/users/') && ! str_starts_with($path, 'storage/')) {
            // bare filename — match admin/web convention
            $path = 'public/uploads/users/' . $path;
        }
        $base = rtrim((string) config('app.url'), '/');
        if ($base === '') {
            $base = 'https://currentsign.com';
        }
        $base = preg_replace('#^http://#i', 'https://', $base);
        return $base . '/' . $path;
    }

    public function passwordReset(Request $request)
    {
        $identity = $request->input('identity') ?: $request->input('email');
        if (! $identity) {
            return $this->sendError($result = null, $message = 'The identity field is required.', $notification = null, $error = null, $respose_code = 200);
        }

        $user = $this->findUserByIdentity($identity);
        if (! $user) {
            return $this->sendError($result = null, $message = 'User not found.', $notification = null, $error = null, $respose_code = 200);
        }

        // Staging/preview: fixed OTP. Production mailer can replace later.
        $rand = '9999';
        $futureDateTime = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $user->otp = $rand;
        $user->otp_time = $futureDateTime;
        $user->otp_verify = 'FALSE';
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'otp_reset_password')) {
            $user->otp_reset_password = $rand;
            $user->otp_reset_password_expiration = $futureDateTime;
        }
        $user->save();

        return $this->sendResponse($result = null, $message = 'Otp send successfully.', $notification = null, $error = null, $respose_code = 200);
    }

    public function verifyOtp(Request $request)
    {
        $identity = $request->input('identity') ?: $request->input('email');
        $otp = $request->input('otp');
        if (! $identity || $otp === null || $otp === '') {
            return $this->sendError($result = null, $message = 'Identity and OTP are required.', $notification = null, $error = null, $respose_code = 200);
        }

        $user = $this->findUserByIdentity($identity);
        if (! $user) {
            return $this->sendError($result = null, $message = 'User not found.', $notification = null, $error = null, $respose_code = 200);
        }

        $expected = $user->otp;
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'otp_reset_password') && $user->otp_reset_password) {
            $expected = $user->otp_reset_password;
        }
        if ((string) $otp !== (string) $expected) {
            return $this->sendError($result = null, $message = 'Otp not matched.', $notification = null, $error = null, $respose_code = 200);
        }

        $expiresAt = $user->otp_time;
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'otp_reset_password_expiration') && $user->otp_reset_password_expiration) {
            $expiresAt = $user->otp_reset_password_expiration;
        }
        $dbTimestamp = strtotime((string) $expiresAt);
        if (! $dbTimestamp || time() > $dbTimestamp) {
            return $this->sendError($result = null, $message = 'Otp Expired.', $notification = null, $error = null, $respose_code = 200);
        }

        // Keep OTP until create-password so reset flow can complete without a session.
        $user->otp_verify = 'TRUE';
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'otp_reset_password')) {
            $user->otp_reset_password = (string) $otp;
            $user->otp_reset_password_expiration = $expiresAt;
        }
        $user->save();

        $success['token'] = '';
        $success['user_data'] = ['id' => $user->id, 'email' => $user->email];
        $success['otp_verified'] = true;

        return $this->sendResponse($result = $success, $message = 'Otp verified successfully.', $notification = null, $error = null, $respose_code = 200);
    }
    
    
    public function createNewPasswordWithoutLogin(Request $request)
    {
        $identity = $request->input('identity') ?: $request->input('email');
        $validator = Validator::make(array_merge($request->all(), ['identity' => $identity]), [
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

        $otp = $request->input('otp');
        $user = $this->findUserByIdentity($identity);
        if (! $user) {
            return $this->sendError($result = null, $message = 'User not found.', $notification = null, $error = null, $respose_code = 200);
        }

        $storedOtp = null;
        $expiresAt = null;
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'otp_reset_password') && $user->otp_reset_password) {
            $storedOtp = $user->otp_reset_password;
            $expiresAt = $user->otp_reset_password_expiration;
        }
        if ($storedOtp === null) {
            $storedOtp = $user->otp;
            $expiresAt = $user->otp_time;
        }

        if ((string) $otp !== (string) $storedOtp) {
            return $this->sendError($result = null, $message = 'Otp not matched.', $notification = null, $error = null, $respose_code = 200);
        }

        $dbTimestamp = strtotime((string) $expiresAt);
        if (! $dbTimestamp || time() > $dbTimestamp) {
            return $this->sendError($result = null, $message = 'Otp Expired.', $notification = null, $error = null, $respose_code = 200);
        }

        $user->password = bcrypt($request->password);
        $user->otp = null;
        $user->otp_time = null;
        $user->otp_verify = 'FALSE';
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'otp_reset_password')) {
            $user->otp_reset_password = null;
            $user->otp_reset_password_expiration = null;
        }
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
        $data = [];
        foreach (['full_name', 'mobile_number', 'address', 'dob', 'country_code'] as $key) {
            if ($request->filled($key)) {
                $data[$key] = $request->input($key);
            }
        }
        // Handle image upload — accept multipart file OR base64 (mobile fallback).
        if ($request->hasFile('image')) {
            $dir = public_path('uploads/users');
            if (! is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            $image = $request->file('image');
            $ext = strtolower($image->getClientOriginalExtension() ?: 'jpg');
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                $ext = 'jpg';
            }
            $imageName = time() . '_' . $user->id . '.' . $ext;
            $image->move($dir, $imageName);
            $data['image'] = $imageName;
        } elseif ($request->filled('image_base64')) {
            $dir = public_path('uploads/users');
            if (! is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            $raw = (string) $request->input('image_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $raw, $m)) {
                $ext = strtolower($m[1]) === 'jpeg' ? 'jpg' : strtolower($m[1]);
                $raw = substr($raw, strpos($raw, ',') + 1);
            } else {
                $ext = 'jpg';
            }
            $bytes = base64_decode($raw, true);
            if ($bytes !== false && strlen($bytes) > 0) {
                if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                    $ext = 'jpg';
                }
                $imageName = time() . '_' . $user->id . '.' . $ext;
                file_put_contents($dir . DIRECTORY_SEPARATOR . $imageName, $bytes);
                $data['image'] = $imageName;
            }
        }

        if (! empty($data)) {
            User::where('id', $user->id)->update($data);
        }
        $user = User::where('id', $user->id)->first();
        $absolute = $this->absoluteUserImageUrl($user->image);
        $user->image = $absolute;
        $user->image_url = $absolute;
        
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
        $u->image = $this->absoluteUserImageUrl($u->image);
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

    /**
     * Issue a Passport personal-access token, repairing missing keys/client once if needed.
     */
    protected function issueApiToken($user): string
    {
        try {
            return $user->createToken('MyApp')->accessToken;
        } catch (\Throwable $e) {
            \Log::warning('createToken failed, repairing Passport', ['error' => $e->getMessage()]);

            $private = storage_path('oauth-private.key');
            $public = storage_path('oauth-public.key');
            if (!is_readable($private) || !is_readable($public)) {
                \Artisan::call('passport:keys', ['--force' => true]);
            }

            try {
                $pac = \Laravel\Passport\Passport::personalAccessClient();
                if (!$pac->exists()) {
                    app(\Laravel\Passport\ClientRepository::class)
                        ->createPersonalAccessClient(null, 'CurrentSign Personal Access Client', 'http://localhost');
                }
            } catch (\Throwable $ignored) {
                app(\Laravel\Passport\ClientRepository::class)
                    ->createPersonalAccessClient(null, 'CurrentSign Personal Access Client', 'http://localhost');
            }

            return $user->createToken('MyApp')->accessToken;
        }
    }

    /**
     * Keep only columns that exist on the production users table.
     */
    protected function filterUserColumns(array $payload): array
    {
        $filtered = [];
        foreach ($payload as $column => $value) {
            if ($value === null) {
                continue;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', $column)) {
                $filtered[$column] = $value;
            }
        }
        return $filtered;
    }
    
  
    
    
}
