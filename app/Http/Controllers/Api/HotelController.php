<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use DB;
class HotelController extends Controller
{
   public function get_hotel(Request $request)
{
    if (!Auth::guard('api')->check()) {
        return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
    }

    $user = Auth::guard('api')->user();
    
    // print_r($user->id);die;

    // Set user image path
    // $user->image = url("/") . "/public/uploads/users/" . $user->image;

    // Retrieve hotels with active status
    $hotels = DB::table('hotel_list')
        ->where('status', 'ACTIVE')
        ->get();

    foreach ($hotels as $hotel) {
        // Set hotel main image path
        $hotel->image = url("/") . "/public/uploads/hotel/" . $hotel->image;

        // Retrieve hotel gallery images
        $galleryImages = DB::table('hotel_gallery')
            ->where('hotel_id', $hotel->id)  // Assuming `hotel_id` links the gallery to the hotel
            ->get();

        // Add full path to each gallery image and append it to the hotel object
        foreach ($galleryImages as $galleryImage) {
            $galleryImage->image = url("/") . "/public/uploads/hotel_gallery/" . $galleryImage->image;
        }

        // Add gallery images to the hotel object
        $hotel->gallery = $galleryImages;
        
         $averageRating = DB::table('reviews')
            ->where('hotel_id', $hotel->id)  // Assuming `hotel_id` links the reviews to the hotel
            ->avg('review_rating');  // Assuming 'rating' is the column storing review ratings

        // Add average rating to the hotel object (default to 0 if no reviews)
        $hotel->average_rating = $averageRating ? round($averageRating, 2) : 0;
        
        
          // Check if this hotel is saved by the user in the saved table
        $isSaved = DB::table('saved')
            ->where('user_id', $user->id)  // Check with the logged-in user's ID
            ->where('hotel_id', $hotel->id)  // Check with the current hotel's ID
            ->exists();

        // Add saved status to the hotel object
        $hotel->is_saved = $isSaved ? true : false;
    
      
    }

    if ($hotels->isNotEmpty()) {
        return $this->sendResponse($result = $hotels, $message = 'Hotels retrieved successfully with galleries.', $notification = [], $error = [], $respose_code = 200);
    } else {
        return $this->sendError($result = [], $message = 'No hotels found.', $notification = [], $error = [], $respose_code = 200);
    }
}

public function get_random_hotels(Request $request)
{
    if (!Auth::guard('api')->check()) {
        return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
    }

    // Retrieve the authenticated user
    $user = Auth::guard('api')->user();

    // Retrieve hotels in random order with active status
    $hotels = DB::table('hotel_list')
        ->where('status', 'ACTIVE')
        ->inRandomOrder() // Randomly orders the hotels
        ->get();

    foreach ($hotels as $hotel) {
        // Set hotel main image path
        $hotel->image = url("/") . "/public/uploads/hotel/" . $hotel->image;

        // Retrieve hotel gallery images
        $galleryImages = DB::table('hotel_gallery')
            ->where('hotel_id', $hotel->id)
            ->get();

        foreach ($galleryImages as $galleryImage) {
            $galleryImage->image = url("/") . "/public/uploads/hotel_gallery/" . $galleryImage->image;
        }

        $hotel->gallery = $galleryImages;

        // Retrieve average rating
        $averageRating = DB::table('reviews')
            ->where('hotel_id', $hotel->id)
            ->avg('review_rating');
        $hotel->average_rating = $averageRating ? round($averageRating, 2) : 0;

        // Check if the hotel is saved by the user
        $isSaved = DB::table('saved')
            ->where('user_id', $user->id)
            ->where('hotel_id', $hotel->id)
            ->exists();
        $hotel->is_saved = $isSaved ? true : false;
    }

    if ($hotels->isNotEmpty()) {
        return $this->sendResponse($result = $hotels, $message = 'Random hotels retrieved successfully.', $notification = [], $error = [], $respose_code = 200);
    } else {
        return $this->sendError($result = [], $message = 'No hotels found.', $notification = [], $error = [], $respose_code = 200);
    }
}

public function usePromoCode(Request $request){
    if (!Auth::guard('api')->check()) {
        return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 401);
    }
     $this->validate($request, [
        'code_name' => 'required',
    ]);
    
    $user = Auth::guard('api')->user(); 
    $promo_code = DB::table('promo_codes')
            ->where('code_name',  $request->input('code_name'))
            ->first();
    if($promo_code){
        $exits = DB::table('used_codes')
            ->where('code_id', $promo_code->id)
            ->where('user_id', $user->id)
            ->first();
            if($exits){
                 return $this->sendError($result = null, $message = 'already Used.', $notification = [], $error = [], $respose_code = 200);
            }else{
                
                 DB::table('used_codes')->insert([
            'user_id' => $user->id,
            'code_id' => $promo_code->id
        ]);
                
                return $this->sendError($result = $promo_code, $message = 'Success.', $notification = [], $error = [], $respose_code = 200);
            }
        
    }else{
       return $this->sendError($result = null, $message = 'Code not found', $notification = [], $error = [], $respose_code = 404); 
    }
    
    
}


public function add_remove_saved_hotel(Request $request)
{
    if (!Auth::guard('api')->check()) {
        return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 401);
    }

    // Validate the request data
    $this->validate($request, [
        'hotel_id' => 'required',
        'action'   => 'required' // Use 'add' to save and 'remove' to delete
    ]);

    // Get the authenticated user
    $user = Auth::guard('api')->user();
    $hotel_id = $request->input('hotel_id');
    $action = $request->input('action');

    // Determine whether to add or remove the saved hotel
    if ($action === 'add') {
        // Add hotel to saved list
        $exists = DB::table('saved')
            ->where('user_id', $user->id)
            ->where('hotel_id', $hotel_id)
            ->exists();

        if ($exists) {
            
            return $this->sendError($result = [], $message = 'Hotel already saved.', $notification = [], $error = [], $respose_code = 200);
        }

        DB::table('saved')->insert([
            'user_id' => $user->id,
            'hotel_id' => $hotel_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return $this->sendResponse($result = [], $message = 'Hotel added to saved list.', $notification = [], $error = [], $respose_code = 200);
    } elseif ($action === 'remove') {
        // Remove hotel from saved list
        $deleted = DB::table('saved')
            ->where('user_id', $user->id)
            ->where('hotel_id', $hotel_id)
            ->delete();

        if ($deleted) {
            return $this->sendResponse($result = [], $message = 'Hotel removed from saved list.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Hotel not found in saved list.', $notification = [], $error = [], $respose_code = 404);
        }
    } else {
        return $this->sendError($result = [], $message = 'Invalid action.', $notification = [], $error = [], $respose_code = 400);
    }
}



public function post_hotel_by_id(Request $request)
{
   
    
    if (!Auth::guard('api')->check()) {
        return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 401);
    }

    // Validate the hotel_id in the request body
    $this->validate($request, [
        'hotel_id' => 'required',
    ]);

    // Get the hotel_id from the request
    $hotel_id = $request->input('hotel_id');
   
  

    // Retrieve the authenticated user
    $user = Auth::guard('api')->user();

    // Retrieve hotel by id
    $hotel = DB::table('hotel_list')
        ->where('id', $hotel_id)
        ->where('status', 'ACTIVE') // Ensure the hotel is active
        ->first();
        
        // echo '<pre>';
        // print_r($hotel);
        // echo '</pre>'; die;

    if (!$hotel) {
        // Return an empty object if no hotel is found
        return $this->sendResponse($result = (object)[], $message = 'Hotel not found.', $notification = [], $error = [], $respose_code = 404);
    }

    // Set hotel main image path
    $hotel->image = url("/") . "/public/uploads/hotel/" . $hotel->image;

    // Retrieve hotel gallery images
    $galleryImages = DB::table('hotel_gallery')
        ->where('hotel_id', $hotel->id)
        ->get();

    foreach ($galleryImages as $galleryImage) {
        $galleryImage->image = url("/") . "/public/uploads/hotel_gallery/" . $galleryImage->image;
    }

    $hotel->gallery = $galleryImages;

    // Retrieve average rating
    $averageRating = DB::table('reviews')
        ->where('hotel_id', $hotel->id)
        ->avg('review_rating');
    $hotel->average_rating = $averageRating ? round($averageRating, 2) : 0;

    // Check if the hotel is saved by the user
    $isSaved = DB::table('saved')
        ->where('user_id', $user->id)
        ->where('hotel_id', $hotel->id)
        ->exists();
    $hotel->is_saved = $isSaved ? true : false;

    return $this->sendResponse($result = $hotel, $message = 'Hotel retrieved successfully.', $notification = [], $error = [], $respose_code = 200);
}




public function get_saved_hotels(Request $request)
{
    if (!Auth::guard('api')->check()) {
        return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
    }

    // Retrieve the authenticated user
    $user = Auth::guard('api')->user();

    // Get the saved hotels for the current user
    $savedHotels = DB::table('saved')
        ->join('hotel_list', 'saved.hotel_id', '=', 'hotel_list.id')
        ->where('saved.user_id', $user->id)
        ->where('hotel_list.status', 'ACTIVE') // Only active hotels
        ->select('hotel_list.*')
        ->get();

    foreach ($savedHotels as $hotel) {
        // Set hotel main image path
        $hotel->image = url("/") . "/public/uploads/hotel/" . $hotel->image;

        // Retrieve hotel gallery images
        $galleryImages = DB::table('hotel_gallery')
            ->where('hotel_id', $hotel->id)
            ->get();

        foreach ($galleryImages as $galleryImage) {
            $galleryImage->image = url("/") . "/public/uploads/hotel_gallery/" . $galleryImage->image;
        }

        $hotel->gallery = $galleryImages;

        // Retrieve average rating
        $averageRating = DB::table('reviews')
            ->where('hotel_id', $hotel->id)
            ->avg('review_rating');
        $hotel->average_rating = $averageRating ? round($averageRating, 2) : 0;

        // Mark the hotel as saved since it's already saved
        $hotel->is_saved = true;
    }

    if ($savedHotels->isNotEmpty()) {
        return $this->sendResponse($result = $savedHotels, $message = 'Saved hotels retrieved successfully.', $notification = [], $error = [], $respose_code = 200);
    } else {
        return $this->sendError($result = [], $message = 'No saved hotels found.', $notification = [], $error = [], $respose_code = 200);
    }
}



public function search_hotels(Request $request)
{
    if (!Auth::guard('api')->check()) {
        return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
    }

    // Validate that the search query is provided
    $this->validate($request, [
        'title_or_description' => 'required|string|max:255',
    ]);

    $searchQuery = $request->input('title_or_description');

    // Retrieve hotels where title or description contains the search query
    $hotels = DB::table('hotel_list')
        ->where('status', 'ACTIVE')
        ->where(function ($query) use ($searchQuery) {
            $query->where('title', 'like', '%' . $searchQuery . '%')
                ->orWhere('description', 'like', '%' . $searchQuery . '%');
        })
        ->get();

    $user = Auth::guard('api')->user();

    foreach ($hotels as $hotel) {
        // Set hotel main image path
        $hotel->image = url("/") . "/public/uploads/hotel/" . $hotel->image;

        // Retrieve hotel gallery images
        $galleryImages = DB::table('hotel_gallery')
            ->where('hotel_id', $hotel->id)
            ->get();

        foreach ($galleryImages as $galleryImage) {
            $galleryImage->image = url("/") . "/public/uploads/hotel_gallery/" . $galleryImage->image;
        }

        $hotel->gallery = $galleryImages;

        // Retrieve average rating
        $averageRating = DB::table('reviews')
            ->where('hotel_id', $hotel->id)
            ->avg('review_rating');
        $hotel->average_rating = $averageRating ? round($averageRating, 2) : 0;

        // Check if the hotel is saved by the user
        $isSaved = DB::table('saved')
            ->where('user_id', $user->id)
            ->where('hotel_id', $hotel->id)
            ->exists();
        $hotel->is_saved = $isSaved ? true : false;
    }

    if ($hotels->isNotEmpty()) {
        return $this->sendResponse($result = $hotels, $message = 'Hotels retrieved successfully based on search.', $notification = [], $error = [], $respose_code = 200);
    } else {
        return $this->sendError($result = [], $message = 'No hotels found for the given search query.', $notification = [], $error = [], $respose_code = 200);
    }
}



public function get_popular_hotels(Request $request)
{
    if (!Auth::guard('api')->check()) {
        return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
    }

    // Retrieve the authenticated user
    $user = Auth::guard('api')->user();

    // Retrieve hotels based on popularity (e.g., most saved/booked)
    $hotels = DB::table('hotel_list')
        ->leftJoin('saved', 'hotel_list.id', '=', 'saved.hotel_id')
        ->select('hotel_list.*', DB::raw('COUNT(saved.hotel_id) as save_count'))  // Count how many times each hotel was saved
        ->where('hotel_list.status', 'ACTIVE')
        ->groupBy('hotel_list.id')
        ->orderByDesc('save_count')  // Order by the most saved
        ->get();

    foreach ($hotels as $hotel) {
        // Set hotel main image path
        $hotel->image = url("/") . "/public/uploads/hotel/" . $hotel->image;

        // Retrieve hotel gallery images
        $galleryImages = DB::table('hotel_gallery')
            ->where('hotel_id', $hotel->id)
            ->get();

        foreach ($galleryImages as $galleryImage) {
            $galleryImage->image = url("/") . "/public/uploads/hotel_gallery/" . $galleryImage->image;
        }

        $hotel->gallery = $galleryImages;

        // Retrieve average rating
        $averageRating = DB::table('reviews')
            ->where('hotel_id', $hotel->id)
            ->avg('review_rating');
        $hotel->average_rating = $averageRating ? round($averageRating, 2) : 0;

        // Check if the hotel is saved by the user
        $isSaved = DB::table('saved')
            ->where('user_id', $user->id)
            ->where('hotel_id', $hotel->id)
            ->exists();
        $hotel->is_saved = $isSaved ? true : false;
    }

    if ($hotels->isNotEmpty()) {
        return $this->sendResponse($result = $hotels, $message = 'Popular hotels retrieved successfully.', $notification = [], $error = [], $respose_code = 200);
    } else {
        return $this->sendError($result = [], $message = 'No popular hotels found.', $notification = [], $error = [], $respose_code = 200);
    }
}



    
    public function get_motorbikes_classes(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $categories = DB::table('motorbikes_classes')
            ->whereNull('motorbike_class_deleted_at')
            ->where('motorbike_class_admin_status', 'ACTIVE')
            ->get();
        if ($categories) {
            return $this->sendResponse($result = $categories, $message = 'Class motorbikes retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class motorbikes not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_trucks_and_heavy_vehicles_classes(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $categories = DB::table('trucks_and_heavy_vehicles_classes')
            ->whereNull('trucks_and_heavy_vehicle_class_deleted_at')
            ->where('trucks_and_heavy_vehicle_class_admin_status', 'ACTIVE')
            ->get();
        if ($categories) {
            return $this->sendResponse($result = $categories, $message = 'Class trucks and heavy vehicles retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class trucks and heavy vehicles not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    
    public function get_cars_body_colors(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_body_colors = DB::table('cars_body_colors')
            ->whereNull('car_bc_deleted_at')
            ->where('car_bc_admin_status', 'ACTIVE')
            ->get();
        if ($cars_body_colors) {
            return $this->sendResponse($result = $cars_body_colors, $message = 'Class Cars Body Colors retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Cars Body Colors not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_motorbikes_body_colors(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_body_colors = DB::table('motorbikes_body_colors')
            ->whereNull('motorbike_bc_deleted_at')
            ->where('motorbike_bc_admin_status', 'ACTIVE')
            ->get();
        if ($cars_body_colors) {
            return $this->sendResponse($result = $cars_body_colors, $message = 'Class motorbikes Body Colors retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class motorbikes Body Colors not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_trucks_and_heavy_vehicles_body_colors(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_body_colors = DB::table('trucks_and_heavy_vehicles_body_colors')
            ->whereNull('trucks_and_heavy_vehicle_bc_deleted_at')
            ->where('trucks_and_heavy_vehicle_bc_admin_status', 'ACTIVE')
            ->get();
        if ($cars_body_colors) {
            return $this->sendResponse($result = $cars_body_colors, $message = 'Class trucks and heavy vehicles Body Colors retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class trucks and heavy vehicles Body Colors not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    
    public function get_cars_cities(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_cities = DB::table('cars_cities')
            ->whereNull('car_city_deleted_at')
            ->where('car_city_admin_status', 'ACTIVE')
            ->get();
        if ($cars_cities) {
            return $this->sendResponse($result = $cars_cities, $message = 'Class Cars Cities retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Cars Cities not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_trucks_and_heavy_vehicles_cities(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_cities = DB::table('trucks_and_heavy_vehicles_cities')
            ->whereNull('trucks_and_heavy_vehicle_city_deleted_at')
            ->where('trucks_and_heavy_vehicle_city_admin_status', 'ACTIVE')
            ->get();
        if ($cars_cities) {
            return $this->sendResponse($result = $cars_cities, $message = 'Class trucks and heavy vehicles Cities retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class trucks and heavy vehicles Cities not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_motorbikes_cities(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_cities = DB::table('motorbikes_cities')
            ->whereNull('motorbike_city_deleted_at')
            ->where('motorbike_city_admin_status', 'ACTIVE')
            ->get();
        if ($cars_cities) {
            return $this->sendResponse($result = $cars_cities, $message = 'Class motorbikes Cities retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class motorbikes Cities not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_cars_makers(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_makers = DB::table('cars_makers')
            ->whereNull('car_maker_deleted_at')
            ->where('car_maker_admin_status', 'ACTIVE')
            ->get();
        if ($cars_makers) {
            return $this->sendResponse($result = $cars_makers, $message = 'Class Cars Makers retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Cars Makers not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_motorbikes_makers(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_makers = DB::table('motorbikes_makers')
            ->whereNull('motorbike_maker_deleted_at')
            ->where('motorbike_maker_admin_status', 'ACTIVE')
            ->get();
        if ($cars_makers) {
            return $this->sendResponse($result = $cars_makers, $message = 'Class motorbikes Makers retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class motorbikes Makers not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_trucks_and_heavy_vehicles_makers(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_makers = DB::table('trucks_and_heavy_vehicles_makers')
            ->whereNull('trucks_and_heavy_vehicle_maker_deleted_at')
            ->where('trucks_and_heavy_vehicle_maker_admin_status', 'ACTIVE')
            ->get();
        if ($cars_makers) {
            return $this->sendResponse($result = $cars_makers, $message = 'Class trucks and heavy vehicles Makers retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class trucks and heavy vehicles Makers not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_cars_years(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_years = DB::table('cars_years')
            ->whereNull('car_years_deleted_at')
            ->where('car_years_admin_status', 'ACTIVE')
            ->get();
        if ($cars_years) {
            return $this->sendResponse($result = $cars_years, $message = 'Class Cars Years retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Cars Years not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_motorbikes_years(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_years = DB::table('motorbikes_years')
            ->whereNull('motorbike_years_deleted_at')
            ->where('motorbike_years_admin_status', 'ACTIVE')
            ->get();
        if ($cars_years) {
            return $this->sendResponse($result = $cars_years, $message = 'Class motorbikes Years retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class motorbikes Years not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_trucks_and_heavy_vehicles_years(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_years = DB::table('trucks_and_heavy_vehicles_years')
            ->whereNull('trucks_and_heavy_vehicle_years_deleted_at')
            ->where('trucks_and_heavy_vehicle_years_admin_status', 'ACTIVE')
            ->get();
        if ($cars_years) {
            return $this->sendResponse($result = $cars_years, $message = 'Class trucks and heavy vehicles Years retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class trucks and heavy vehicles Years not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_cars_types(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_types = DB::table('cars_types')
            ->whereNull('car_type_deleted_at')
            ->where('car_type_admin_status', 'ACTIVE')
            ->get();
        if ($cars_types) {
            return $this->sendResponse($result = $cars_types, $message = 'Class Cars Types retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Cars Types not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_motorbikes_types(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_types = DB::table('motorbikes_types')
            ->whereNull('motorbike_type_deleted_at')
            ->where('motorbike_type_admin_status', 'ACTIVE')
            ->get();
        if ($cars_types) {
            return $this->sendResponse($result = $cars_types, $message = 'Class motorbikes Types retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class motorbikes Types not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_trucks_and_heavy_vehicles_types(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_types = DB::table('trucks_and_heavy_vehicles_types')
            ->whereNull('trucks_and_heavy_vehicle_type_deleted_at')
            ->where('trucks_and_heavy_vehicle_type_admin_status', 'ACTIVE')
            ->get();
        if ($cars_types) {
            return $this->sendResponse($result = $cars_types, $message = 'Class trucks and heavy vehicles Types retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class trucks and heavy vehiclees Types not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_categories(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $categories = DB::table('categories')
            ->whereNull('cat_deleted_at')
            ->where('cat_admin_status', 'ACTIVE')
            ->get();
        if ($categories) {
            return $this->sendResponse($result = $categories, $message = 'Class Categories retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Categories not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    public function get_cities(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cities = DB::table('cities')
            ->whereNull('city_deleted_at')
            ->where('city_admin_status', 'ACTIVE')
            ->get();
        if ($cities) {
            return $this->sendResponse($result = $cities, $message = 'Class Cities retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Cities not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    public function get_packages(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $packages = DB::table('packages')
            ->whereNull('package_deleted_at')
            ->where('package_admin_status', 'ACTIVE')
            ->get();
        if ($packages) {
            return $this->sendResponse($result = $packages, $message = 'Class Packages retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Packages not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    public function get_privacy_policies(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $privacy_policies = DB::table('privacy_policies')
            ->whereNull('pp_deleted_at')
            ->where('pp_admin_status', 'ACTIVE')
            ->get();
        if ($privacy_policies) {
            return $this->sendResponse($result = $privacy_policies[0], $message = 'Class Privacy Policies retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Privacy Policies not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    public function get_sub_categories(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $category_id = $request['category_id'];
        if (!$category_id) {
            return $this->sendError($result = [], $message = 'category_id Field Required.', $notification = [], $error = [], $respose_code = 200);
        }
        $sub_categories = DB::table('sub_categories')
            ->whereNull('sub_cat_deleted_at')
            ->where('sub_cat_admin_status', 'ACTIVE')
            ->where('sub_cat_category_id', $category_id)
            ->get();
        if ($sub_categories) {
            return $this->sendResponse($result = $sub_categories, $message = 'Class Sub Categories retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Sub Categories not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_cars_models(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $macker_id = $request['macker_id'];
        if (!$macker_id) {
            return $this->sendError($result = [], $message = 'macker_id Field Required.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_models = DB::table('cars_models')
            ->whereNull('car_model_deleted_at')
            ->where('car_model_admin_status', 'ACTIVE')
            ->where('car_model_car_maker_id', $macker_id)
            ->get();
        if ($cars_models) {
            return $this->sendResponse($result = $cars_models, $message = 'Class Cars Models retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Cars Models not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_motorbikes_models(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $macker_id = $request['macker_id'];
        if (!$macker_id) {
            return $this->sendError($result = [], $message = 'macker_id Field Required.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_models = DB::table('motorbikes_models')
            ->whereNull('motorbike_model_deleted_at')
            ->where('motorbike_model_admin_status', 'ACTIVE')
            ->where('motorbike_model_car_maker_id', $macker_id)
            ->get();
        if ($cars_models) {
            return $this->sendResponse($result = $cars_models, $message = 'Class motorbikes Models retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class motorbikes Models not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_trucks_and_heavy_vehicles_models(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $macker_id = $request['macker_id'];
        if (!$macker_id) {
            return $this->sendError($result = [], $message = 'macker_id Field Required.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_models = DB::table('trucks_and_heavy_vehicles_models')
            ->whereNull('trucks_and_heavy_vehicle_model_deleted_at')
            ->where('trucks_and_heavy_vehicle_model_admin_status', 'ACTIVE')
            ->where('trucks_and_heavy_vehicle_model_car_maker_id', $macker_id)
            ->get();
        if ($cars_models) {
            return $this->sendResponse($result = $cars_models, $message = 'Class trucks and heavy vehicles Models retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class trucks and heavy vehicles Models not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_cars_extra_features(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_extra_features = DB::table('cars_extra_features')
            ->whereNull('car_extra_feature_deleted_at')
            ->where('car_extra_feature_admin_status', 'ACTIVE')
            ->get();
        if ($cars_extra_features) {
            return $this->sendResponse($result = $cars_extra_features, $message = 'Class Cars Extra Features retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Cars Extra Features not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_motorbikes_extra_features(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_extra_features = DB::table('motorbikes_extra_features')
            ->whereNull('motorbike_extra_feature_deleted_at')
            ->where('motorbike_extra_feature_admin_status', 'ACTIVE')
            ->get();
        if ($cars_extra_features) {
            return $this->sendResponse($result = $cars_extra_features, $message = 'Class motorbikes Extra Features retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class motorbikes Extra Features not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
     
    public function get_banners(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $banners = DB::table('banners')
            ->whereNull('ban_deleted_at')
            ->where('ban_admin_status', 'ACTIVE')
            ->get();
        if ($banners) {
            return $this->sendResponse($result = $banners, $message = 'Class Banners retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Banners not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_cars_plates_codes(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_plates_codes = DB::table('cars_plates_codes')
            ->whereNull('car_plate_code_deleted_at')
            ->where('car_plate_code_admin_code', 'ACTIVE')
            ->get();
        if ($cars_plates_codes) {
            return $this->sendResponse($result = $cars_plates_codes, $message = 'Class Cars Plates Codes retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Cars Plates Codes not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    // public function get_cars_plates_codes(Request $request)
    // {
    //     if (!Auth::guard('api')->check()) {
    //         return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
    //     }
    //     $cars_plates_codes = DB::table('cars_plates_codes')
    //         ->whereNull('car_plate_code_deleted_at')
    //         ->where('car_plate_code_admin_code', 'ACTIVE')
    //         ->get();
    //     if ($cars_plates_codes) {
    //         return $this->sendResponse($result = $cars_plates_codes, $message = 'Class Cars Plates Codes retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
    //     } else {
    //         return $this->sendError($result = [], $message = 'Class Cars Plates Codes not found.', $notification = [], $error = [], $respose_code = 200);
    //     }
    // }
    
    public function get_cars_plate_designs(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_plate_designs = DB::table('cars_plate_designs')
            ->whereNull('car_plate_design_deleted_at')
            ->where('car_plate_design_admin_status', 'ACTIVE')
            ->get();
        if ($cars_plate_designs) {
            return $this->sendResponse($result = $cars_plate_designs, $message = 'Class Cars Plate Designs retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Cars Plate Designs not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    public function get_cars_plate_sources(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_plate_sources = DB::table('cars_plate_sources')
            ->whereNull('car_plate_source_deleted_at')
            ->where('car_plate_source_admin_status', 'ACTIVE')
            ->get();
        if ($cars_plate_sources) {
            return $this->sendResponse($result = $cars_plate_sources, $message = 'Class Cars Plate Sources retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Cars Plate Sources not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    public function get_cars_rim_sizes(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_rim_sizes = DB::table('cars_rim_sizes')
            ->whereNull('car_rim_size_deleted_at')
            ->where('car_rim_size_admin_status', 'ACTIVE')
            ->get();
        if ($cars_rim_sizes) {
            return $this->sendResponse($result = $cars_rim_sizes, $message = 'Class Cars Rim Sizes retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class Cars Rim Sizes not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_motorbikes_rim_sizes(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 200);
        }
        $cars_rim_sizes = DB::table('motorbikes_rim_sizes')
            ->whereNull('motorbike_rim_size_deleted_at')
            ->where('motorbike_rim_size_admin_status', 'ACTIVE')
            ->get();
        if ($cars_rim_sizes) {
            return $this->sendResponse($result = $cars_rim_sizes, $message = 'Class motorbikes Rim Sizes retrieve successfully.', $notification = [], $error = [], $respose_code = 200);
        } else {
            return $this->sendError($result = [], $message = 'Class motorbikes Rim Sizes not found.', $notification = [], $error = [], $respose_code = 200);
        }
    }
    
    public function get_car_by_classes_id(Request $request)
    {
        $class_id = $request->input('class_id');
        if (!Auth::guard('api')->check()) {
            return $this->sendError([], 'Unauthorized.', [], [], 200);
        }
        $cars = DB::table('cars')
            ->select('*')
            ->where('car_class_id', $class_id)
            ->whereNull('car_deleted_at')
            ->where('car_admin_status', 'ACTIVE')
            ->get();
        if ($cars->isEmpty()) {
            return $this->sendError([], 'Class Categories not found.', [], [], 200);
        }
        foreach ($cars as $car) {
            // Fetch related images
            $images = DB::table('cars_images')
                ->select('*')
                ->where('car_image_car_id', $car->car_id)
                ->whereNull('car_image_deleted_at')
                ->where('car_image_admin_status', 'ACTIVE')
                ->get();
            $images_array = [];
            foreach ($images as $image) {
                $image->car_image_name = url("/public/uploads/cars/") . '/' . $image->car_image_name;
                $images_array[] = $image;
            }
            $car->images = $images_array;
            $car->car_class_name = "class_name"; //DB::table('car_classes')->where('car_class_id', $car->car_class_id)->value('car_class_name');
            $car->car_user_name = "user_name"; //DB::table('users')->where('id', $car->car_user_id)->value('name');
            $car->car_maker_name = "maker_name"; //DB::table('cars_makers')->where('car_maker_id', $car->car_maker_id)->value('car_maker_name');
            $car->car_model_name = "model_name"; //DB::table('cars_models')->where('car_model_id', $car->car_model_id)->value('car_model_name');
            $car->car_model_year = "model_year"; //DB::table('car_model_years')->where('car_model_year_id', $car->car_model_year_id)->value('car_model_year');
            $car->car_condition_name = "condition_name"; //DB::table('car_conditions')->where('car_condition_id', $car->car_condition_id)->value('car_condition_name');
            $car->car_engine_capacity_name = "engine_capacity_name"; //DB::table('car_engine_capacities')->where('car_engine_capacity_id', $car->car_engine_capacity_id)->value('car_engine_capacity_name');
            $car->car_cylinder_name = "cylinder_name"; //DB::table('car_cylinders')->where('car_cylinder_id', $car->car_cylinder_id)->value('car_cylinder_name');
            $car->car_transmission_name = "transmission_name"; //DB::table('car_transmissions')->where('car_transmission_id', $car->car_transmission_id)->value('car_transmission_name');
            $car->car_body_color_name = "body_color_name"; //DB::table('car_body_colors')->where('car_body_color_id', $car->car_body_color_id)->value('car_body_color_name');
            $car->car_fuel_type_name = "fuel_type_name"; //DB::table('car_fuel_types')->where('car_fuel_type_id', $car->car_fuel_type_id)->value('car_fuel_type_name');
            $car->car_door_name = "door_name"; //DB::table('car_doors')->where('car_door_id', $car->car_door_id)->value('car_door_name');
            $car->car_city_name = "city_name"; //DB::table('car_cities')->where('car_city_id', $car->car_city_id)->value('car_city_name');
            $car->car_plates_code_name = "plates_code_name"; //DB::table('car_plates_codes')->where('car_plate_code_id', $car->car_plates_codes_id)->value('car_plate_code_name');
            $car->car_plate_design_name = "plate_design_name"; //DB::table('car_plate_designs')->where('car_plate_design_id', $car->car_plate_design_id)->value('car_plate_design_name');
            $car->car_plate_source_name = "plate_source_name"; //DB::table('car_plate_sources')->where('car_plate_source_id', $car->car_plate_source_id)->value('car_plate_source_name');
            $car->car_rim_size_name = "rim_size_name"; //DB::table('car_rim_sizes')->where('car_rim_size_id', $car->car_rim_sizes_id)->value('car_rim_size_name');
            $car->car_type_name = "type_name"; //DB::table('car_types')->where('car_type_id', $car->car_type_id)->value('car_type_name');
            $car->car_under_warranty_name = "under_warranty_name"; //DB::table('car_under_warranties')->where('car_under_warranty_id', $car->car_under_warranty_id)->value('car_under_warranty_name');
        }
        return $this->sendResponse($cars, 'Class Categories retrieved successfully.', [], [], 200);
    }
    
    
    
    
    
    
    
    public function get_cars_by_premium_ads(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError([], 'Unauthorized.', [], [], 200);
        }
        $cars = DB::table('cars')
            ->select('car_id')
            ->whereNull('car_deleted_at')
            ->where('car_admin_status', 'ACTIVE')
            ->get();
        if ($cars->isEmpty()) {
            return $this->sendError([], 'Cars not found.', [], [], 200);
        }
        $cars_array = [];
        foreach ($cars as $car) {
            $car_details = $this->get_cars_detail_by_id($car->car_id, "API" , "get_cars_by_premium_ads");
            $cars_array[] = $car_details;
        }
        if ($cars_array == []) {
            return $this->sendResponse([], 'Cars not found.', [], [], 200);
        } else {
            return $this->sendResponse($cars_array, 'Cars found successfully.', [], [], 200);
        }
    }
    
    
    
    
    public function get_cars_by_related_ads(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError([], 'Unauthorized.', [], [], 200);
        }
        $cars = DB::table('cars')
            ->select('car_id')
            ->whereNull('car_deleted_at')
            ->where('car_admin_status', 'ACTIVE')
            ->inRandomOrder()
            ->get();
            
        if ($cars->isEmpty()) {
            return $this->sendError([], 'Cars not found.', [], [], 200);
        }
        $cars_array = [];
        foreach ($cars as $car) {
            $car_details = $this->get_cars_detail_by_id($car->car_id, "API" , "get_cars_by_premium_ads");
            $cars_array[] = $car_details;
        }
        if ($cars_array == []) {
            return $this->sendResponse([], 'Cars not found.', [], [], 200);
        } else {
            return $this->sendResponse($cars_array, 'Cars found successfully.', [], [], 200);
        }
    }

   public function get_car_details_by_car_id(Request $request)
    {
          $car_id = $request->input('car_id');
          
          $data = $this->get_cars_detail_by_id($car_id, "API" , "get_car_details_by_car_id");
          
            if ($data) {
                return $this->sendResponse($data, 'Cars found successfully.', [], [], 200);
            } else {
                return $this->sendResponse([], 'Car not found.', [], [], 200);
            }
          
    }
    
    public function get_cars_detail_by_id($car_id, $call_type = "API" , $function_name = "get_cars_detail_by_id")
    {
        /* Used In
          app/Http/Controllers/Api/FavouriteController.php
          app/Http/Controllers/Api/Automotive/Cars/CarController.php
        */
        
        
        $car = DB::table('cars')
            ->select('*')
            ->where('car_id', $car_id)
            ->first();
        if ($car) {
            $images = DB::table('cars_images')
                ->select('*')
                ->where('car_image_car_id', $car_id)
                ->whereNull('car_image_deleted_at')
                ->where('car_image_admin_status', 'ACTIVE')
                ->get();
            $images_array = [];
            foreach ($images as $image) {
                $image->car_image_name = url("/public/uploads/cars/") . '/' . $image->car_image_name;
                $images_array[] = $image;
            }
            $car->images = $images_array;
            $car_class_name = $car->car_class_name = DB::table('cars_classes')
                ->where('car_class_id', $car->car_class_id)
                ->value('car_class_name');
                
            $car_user_name = $car->car_user_name = DB::table('users')
                ->where('id', $car->car_user_id)
                ->value('full_name');
                
            $car_email = $car->email = DB::table('users')
                ->where('id', $car->car_user_id)
                ->value('email');
                
            $car_mobile_number = $car->car_mobile_number = DB::table('users')
                ->where('id', $car->car_user_id)
                ->value('mobile_number');
                
            $car_country_code = $car->car_country_code = DB::table('users')
                ->where('id', $car->car_user_id)
                ->value('country_code');
                
            $car_maker_name = $car->car_maker_name = DB::table('cars_makers')
                ->where('car_maker_id', $car->car_maker_id)
                ->value('car_maker_name');
            $car_model_name = $car->car_model_name = DB::table('cars_models')
                ->where('car_model_id', $car->car_model_id)
                ->value('car_model_name');
            $car_model_year = $car->car_model_year = DB::table('cars_years')
                ->where('car_year_id', $car->car_model_year_id)
                ->value('car_year_name');
            $car_condition_name = $car->car_condition_name = DB::table('cars_conditions')
                ->where('car_condition_id', $car->car_condition_id)
                ->value('car_condition_name');
            $car_engine_capacity_value = $car->car_engine_capacity_value = DB::table('cars_engine_capacities')
                ->where('car_engine_capacity_id', $car->car_engine_capacity_id)
                ->value('car_engine_capacity_value');
            $car_cylinder_count = $car->car_cylinder_count = DB::table('cars_cylinders')
                ->where('car_cylinder_id', $car->car_cylinder_id)
                ->value('car_cylinder_count');
            $car_body_color_name = $car->car_body_color_name = DB::table('cars_body_colors')
                ->where('car_body_color_id', $car->car_body_color_id)
                ->value('car_body_color_name');
            $car_city_name = $car->car_city_name = DB::table('cars_cities')
                ->where('car_city_id', $car->car_city_id)
                ->value('car_city_name');
            $car_transmission_name = $car->car_transmission_name = DB::table('cars_transmissions')
                ->where('car_transmission_id', $car->car_transmission_id)
                ->value('car_transmission_name');
            $car_fuel_type_name = $car->car_fuel_type_name = DB::table('cars_fuel_types')
                ->where('car_fuel_type_id', $car->car_fuel_type_id)
                ->value('car_fuel_type_name');
            $car_door_count = $car->car_door_count = DB::table('cars_doors')
                ->where('car_door_id', $car->car_door_id)
                ->value('car_door_count');
                
            // $car_plates_code_name = $car->car_plates_code_name = DB::table('cars_plate_codes')
            //     ->where('car_plate_code_id', $car->car_plates_codes_id)
            //     ->value('car_plate_code_name');
                
            // $car_plate_design_name = $car->car_plate_design_name = DB::table('cars_plate_designs')
            //     ->where('car_plate_design_id', $car->car_plate_design_id)
            //     ->value('car_plate_design_name');
                
            // $car_plate_source_name = $car->car_plate_source_name = DB::table('cars_plate_sources')
            //     ->where('car_plate_source_id', $car->car_plate_source_id)
            //     ->value('car_plate_source_name');
                
            $car_rim_size_name = $car->car_rim_size_name = DB::table('cars_rim_sizes')
                ->where('car_rim_size_id', $car->car_rim_sizes_id)
                ->value('car_rim_size_name');
            $car_type_name = $car->car_type_name = DB::table('cars_types')
                ->where('car_type_id', $car->car_type_id)
                ->value('car_type_name');
            $car_under_warranty_name = $car->car_under_warranty_name = DB::table('cars_under_warranties')
                ->where('car_under_warranty_id', $car->car_under_warranty_id)
                ->value('car_under_warranty_name');
                
            $car_mileage   = $car->car_mileage = $car->car_kilometers;
            
            $car_regional_specs = $car->car_regional_specs =  DB::table('cars_regional_specs')
                ->where('car_regional_specs_id', $car->car_regional_specs_id)
                ->value('car_regional_specs_name');
                
            
            $car_interior_color_name  = $car->car_interior_color_name = DB::table('cars_interior_colors')
                ->where('car_interior_color_id', $car->car_interior_color_id)
                ->value('car_interior_color_name');
            
            $car->car_price_formated =  number_format($car->car_price,2);
            $car->car_rent_price_formated = number_format($car->car_rent_price,2);
            
            
            $car_country_code_value  = $car->car_country_code_value = "+" . DB::table('countries')
                ->where('iso', $car->car_country_code)
                ->value('phonecode');
            

            
/*
- Make
- Model (Trim)
- Class
- Model Year
- Milage
- Price
- Monthly Installment
- Condition
- Under Warranty
- Regional Specs
- Body Color
- Interior Color
- Doors
- Engine Capacity
- Cylinders
- Transmission
- Fuel Type
- City
*/            
            
            
            
            
            $car->overview = [
                
                ["name" => "Maker", "image" => url("/")."/public/icons/DBX_Icons/Bodycolor@3x.png", "value" => $car_maker_name],
                ["name" => "Model (Trim)", "image" => url("/")."/public/icons/DBX_Icons/Model@3x.png", "value" => $car_model_name],
                ["name" => "Class", "image" => url("/")."/public/icons/DBX_Icons/Bodycolor@3x.png", "value" => $car_class_name],
                ["name" => "Model Year", "image" => url("/")."/public/icons/DBX_Icons/Age@3x.png", "value" => $car_model_year],
                ["name" => "Mileage", "image" => url("/")."/public/icons/DBX_Icons/Kilometer@3x.png", "value" => $car_mileage],
                ["name" => "Price", "image" => url("/")."/public/icons/DBX_Icons/Price@3x.png", "value" => number_format($car->car_price,2)],
                ["name" => "Monthly Installment", "image" => url("/")."/public/icons/DBX_Icons/Price@3x.png", "value" => number_format($car->car_rent_price,2)],
                ["name" => "Condition", "image" => url("/")."/public/icons/DBX_Icons/Condition@3x.png", "value" => $car_condition_name],
                ["name" => "Under Warranty", "image" => url("/")."/public/icons/DBX_Icons/Warranty@3x.png", "value" => $car_under_warranty_name],
                ["name" => "Regional Specs", "image" => url("/")."/public/icons/DBX_Icons/Rigonal@3x.png", "value" => $car_regional_specs],
                ["name" => "Body Color", "image" => url("/")."/public/icons/DBX_Icons/Bodycolor@3x.png", "value" => $car_body_color_name],
                ["name" => "Interior Color", "image" => url("/")."/public/icons/DBX_Icons/Bodycolor@3x.png", "value" => $car_interior_color_name],
                ["name" => "Doors", "image" => url("/")."/public/icons/DBX_Icons/Door@3x.png", "value" => $car_door_count],
                ["name" => "Engine Capacity", "image" => url("/")."/public/icons/DBX_Icons/Engine@3x.png", "value" => $car_engine_capacity_value],
                ["name" => "Cylinders", "image" => url("/")."/public/icons/DBX_Icons/Bodycolor@3x.png", "value" => $car_cylinder_count],
                ["name" => "Transmission", "image" => url("/")."/public/icons/DBX_Icons/Transmission@3x.png", "value" => $car_transmission_name],
                ["name" => "Fuel Type", "image" => url("/")."/public/icons/DBX_Icons/Fuel@3x.png", "value" => $car_fuel_type_name],
                ["name" => "City", "image" => url("/")."/public/icons/DBX_Icons/City@3x.png", "value" => $car_city_name],

                
                
                

            ];
        }
        
        
        
        $car_horse_power = $car->car_horse_power_value = DB::table('cars_horses_powers')
                ->where('car_horse_power_id', $car->car_horse_power_id)
                ->value('car_horse_power_name');
                
        $car_service_hystory = $car->car_service_hystory_value = DB::table('cars_services_histories')
                ->where('car_service_history_id', $car->car_service_history_id)
                ->value('car_service_history_name');
                
        
        
        $car_drive_type = $car->car_drive_type_value = DB::table('cars_drives_types')
                ->where('car_drive_type_id', $car->car_drive_type_id)
                ->value('car_drive_type_name');
                
            
        $car_extra_feature = $car->car_extra_feature_value = DB::table('cars_extra_features')
                ->where('car_extra_feature_id', $car->car_extra_feature_id)
                ->value('car_extra_feature_name');
            
        
        $car_seats = $car->car_seats_value = DB::table('cars_seats')
                ->where('car_seat_id', $car->car_seat_id)
                ->value('car_seat_name');
            
        
        $car_additional_details = $car->car_additional_details = ($car->car_additional_details != null)?$car->car_additional_details:"";
        
        $car_advertisement_id_favourite = $car->car_advertisement_id_favourite = false;

        
        

        $car->features = [
            ["name" => "Body Type", "image" => url("/")."/public/icons/DBX_Icons/Bodycolor@3x.png", "value" => $car_type_name],
            ["name" => "Horse Power", "image" => url("/")."/public/icons/DBX_Icons/Bodycolor@3x.png", "value" => $car_horse_power],
            ["name" => "Service Hystory", "image" => url("/")."/public/icons/DBX_Icons/Bodycolor@3x.png", "value" => $car_service_hystory],
            ["name" => "Rim Size", "image" => url("/")."/public/icons/DBX_Icons/Bodycolor@3x.png", "value" => $car_rim_size_name],
            ["name" => "Drive Type", "image" => url("/")."/public/icons/DBX_Icons/Bodycolor@3x.png", "value" => $car_drive_type],
            ["name" => "Extra Feature", "image" => url("/")."/public/icons/DBX_Icons/Bodycolor@3x.png", "value" => $car_extra_feature],
            ["name" => "Seats", "image" => url("/")."/public/icons/DBX_Icons/Bodycolor@3x.png", "value" => $car_seats],
            ["name" => "Additional Details ", "image" => url("/")."/public/icons/DBX_Icons/Bodycolor@3x.png", "value" => $car_additional_details],
        ];
        
        
        $owner_details_data = DB::table('users')
                ->where('id', $car->car_user_id)
                ->get();
                
                
        @$owner_details_data[0]->image =     url("/") . "/" . "public/uploads/users" . "/" .     @$owner_details_data[0]->image;

        
        $car->owner_details = @$owner_details_data[0];
        
        $result_review = DB::select("SELECT * FROM `reviews` order by review_id desc limit 10");
        foreach ($result_review as $result_review_in) {
            $review_user_details = DB::select("SELECT * FROM `users` where id = " . $result_review_in->review_user_id);
            $result_review_in->review_user_name = @$review_user_details[0]->full_name;
            $result_review_in->review_user_image = url("/") . "/" . "public/uploads/users" . "/" . @$review_user_details[0]->image;
        }
        $car->reviews = $result_review;
        return $car;
    }
}
