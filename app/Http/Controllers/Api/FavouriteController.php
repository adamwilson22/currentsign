<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use DB;
use App\Http\Controllers\Api\Automotive\Cars\CarController;

    
class FavouriteController extends Controller
{
    
    
public function add_ratting(Request $request)
    {
         if (!Auth::guard('api')->check()) {
        return $this->sendError([], 'Unauthorized.', [], [], 401);
    }

    $user_id = Auth::guard('api')->id();

    $validator = Validator::make($request->all(), [
        'hotel_id' => 'required',
    ]);

    if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return $this->sendError([], $errors[0], [], $errors, 400);
    }

    $advertisement_id = $request->input('hotel_id');
    $review_text = $request->input('review_text');
    $rating = $request->input('rating');

    // Check if the favorite already exists
    $existingFavourite = DB::table('reviews')
        ->where('user_id', $user_id)
        ->where('hotel_id', $advertisement_id)
        ->first();

    if ($existingFavourite) {
        // If the favorite exists, delete it
        $deleted = DB::table('reviews')
            ->where('hotel_id', $existingFavourite->hotel_id)
             ->where('user_id', $existingFavourite->user_id)
            ->delete();

        if ($deleted) {
            return $this->sendResponse([], 'Ratting deleted successfully.', [], [], 200);
        } else {
            return $this->sendError([], 'Failed to delete Ratting.', [], [], 200);
        }
    } else {
        // If the favorite does not exist, add it
        $data = [
            'user_id' => $user_id,
            'hotel_id' => $advertisement_id,
            'review_text' => $review_text,
            'review_rating' => $rating,
            'review_created_at' => now(),
            'review_updated_at' => now(),
            'review_admin_status' => 'ACTIVE',
        ];

        $inserted = DB::table('reviews')->insert($data);

        if ($inserted) {
            return $this->sendResponse([], 'Ratting added successfully.', [], [], 200);
        } else {
            return $this->sendError([], 'Failed to add Ratting.', [], [], 200);
        }
    }
}




public function getHotelReviews(Request $request)
{
    $validator = Validator::make($request->all(), [
        'hotel_id' => 'required',
    ]);

    if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return $this->sendError([], $errors[0], [], $errors, 400);
    }

    $hotel_id = $request->input('hotel_id');

    // Fetch reviews for the specified hotel along with user details
    $reviews = DB::table('reviews')
        ->join('users', 'reviews.user_id', '=', 'users.id')
        ->select(
            'reviews.review_text',
            'reviews.review_rating',
            'reviews.review_created_at',
            'users.first_name as first_name',
             'users.last_name as last_name',
            // 'users.username',
            'users.image'
        )
        ->where('reviews.hotel_id', $hotel_id)
        ->get();

    // Modify the image link
    $reviews->transform(function ($review) {
        $review->image = url("/") . "/public/uploads/users/" . $review->image;
        return $review;
    });

    // If no reviews found
    if ($reviews->isEmpty()) {
        return $this->sendError([], 'No reviews found for this hotel.', [], [], 404);
    }

    return $this->sendResponse($reviews, 'Hotel reviews fetched successfully.', [], [], 200);
}









    public function delete_favourite(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError([], 'Unauthorized.', [], [], 200);
        }

        $user_id = Auth::guard('api')->id();

        $validator = Validator::make($request->all(), [
            'advertisement_id' => 'required|numeric|exists:advertisements,advertisement_id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return $this->sendError([], $errors[0], [], $errors, 200);
        }

        $advertisement_id = $request->input('advertisement_id');

        $deleted = DB::table('favourites')
            ->where('favourite_user_id', $user_id)
            ->where('favourite_advertisement_id', $advertisement_id)
            ->delete();

        if ($deleted) {
            return $this->sendResponse([], 'Favourite deleted successfully.', [], [], 200);
        } else {
            return $this->sendError([], 'Failed to delete favourite.', [], [], 200);
        }
    }

    public function list_favourite(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return $this->sendError([], 'Unauthorized.', [], [], 200);
        }

        $user_id = Auth::guard('api')->id();

        $favourites = DB::table('favourites')
            ->join('advertisements', 'favourites.favourite_advertisement_id', '=', 'advertisements.advertisement_id')
            ->where('favourites.favourite_user_id', $user_id)
            ->select('advertisements.*')
            ->get();
            
            foreach($favourites as $favourites_in){
                
                if($favourites_in->advertisement_type == "CARS"){
                    
                     $ads_details =  DB::table('cars')->where('car_advertisement_id',$favourites_in->advertisement_id)->first();
                     
                     if($ads_details){
                     $car = new CarController();
                     $favourites_in->advertisement_details =  $car->get_cars_detail_by_id($ads_details->car_id, "API" , "list_favourite" );
                     }
                     
                }
                
                
  
                
            }
            

        if ($favourites->isEmpty()) {
            return $this->sendError([], 'No favourites found.', [], [], 200);
        }

        return $this->sendResponse($favourites, 'Favourites retrieved successfully.', [], [], 200);
    }













}

