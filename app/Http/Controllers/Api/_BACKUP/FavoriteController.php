<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Validator;
use Hash;

class FavoriteController extends Controller
{


    public function addFavoriteRestaurant(Request $request)
    {
        // Check if the user is authenticated
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 401);
        }        
        
        // Retrieve the authenticated user
        $user = Auth::guard('api')->user();
        
        // Extract the user ID from the authenticated user
        $user_id = $user->id;
        
        // Retrieve the restaurant ID from the request
        $restaurants_id = $request->input('restaurants_id');
        
        // Check if the restaurant already exists in the user's favorites
        $existingFavorite = DB::table('restaurants_favourites')
            ->where('resfav_user_id', $user_id)
            ->where('resfav_restaurants_id', $restaurants_id)
            ->exists();
    
        // If the restaurant already exists in favorites, do nothing
        if ($existingFavorite) {
            
       DB::table('restaurants_favourites')->update(['resfav_deleted_at'=>NULL],[
            'resfav_restaurants_id' => $restaurants_id,
            'resfav_user_id' => $user_id
        ]);
            
            return $this->sendResponse($result = [], $message = 'Restaurant added to favorites successfully.', $notification = [], $error = [], $respose_code = 200);
        }
        
        // Insert the restaurant ID and user ID into the database table for favorites
        DB::table('restaurants_favourites')->insert([
            'resfav_restaurants_id' => $restaurants_id,
            'resfav_user_id' => $user_id
        ]);
        
        // Return a success response
        return $this->sendResponse($result = [], $message = 'Restaurant added to favorites successfully.', $notification = [], $error = [], $respose_code = 200);
    }
   
    public function deleteFavoriteRestaurant(Request $request)
    {
        // Check if the user is authenticated
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 401);
        }        
    
        $user = Auth::guard('api')->user();
        $user_id = $user->id;
        
        // Retrieve the restaurant ID from the request
        $restaurants_id = $request->input('restaurants_id');
    
        // Soft delete the record by updating the deleted_at column
        $deleted = DB::table('restaurants_favourites')
            ->where('resfav_user_id', $user_id)
            ->where('resfav_restaurants_id', $restaurants_id)
            ->update(['resfav_deleted_at' => now()]);
    
        if($deleted) {
            return $this->sendResponse($result = [], $message = 'Restaurant deleted successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Restaurant not found in favorites.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
   public function listFavoriteRestaurant(Request $request)
    {
        
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 401);
        }        
        
        $user = Auth::guard('api')->user();
        
        $user_id = $user->id;
        
        $sql = "SELECT * FROM `restaurants_favourites`  LEFT JOIN  `restaurants` ON `restaurants_favourites`.`resfav_restaurants_id` = `restaurants`.`res_id` where  `resfav_user_id` = '$user_id' and `resfav_deleted_at` IS NULL and `res_admin_status` = 'ACTIVE' order by res_id desc";
        
        $restaurants = DB::select($sql);
     
        if(is_array($restaurants) && !empty($restaurants)){
        foreach($restaurants as $restaurantsIn){
            $restaurantsIn->res_image = url("/") . "/storage/app/restaurants/".  $restaurantsIn->res_image;
        }
        
        return $this->sendResponse($result = $restaurants, $message = 'Favorite Restaurant data.', $notification = [], $error = [], $respose_code = 200);

        }else{
        return $this->sendError($result = [], $message = 'Favorite Restaurant data not found.', $notification = [], $error = [], $respose_code = 200);
        }
        
    }     
    
}
