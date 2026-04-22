<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use DB;
class BookingController extends Controller
{
    
   public function add_booking(Request $request)
{
    // Check if the user is authenticated
    if (!Auth::guard('api')->check()) {
        return $this->sendError([], 'Unauthorized.', [], [], 401);
    }

    $user_id = Auth::guard('api')->id();

    // Validate input fields
    $validator = Validator::make($request->all(), [
        'hotel_id' => 'required',  // Assuming you have a hotels table
        'adults' => 'required',
        'children' => 'required',
        'booking_start_date' => 'required',  // Expecting an array of booking dates
    ]);

    if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return $this->sendError([], $errors[0], [], $errors, 200);
    }

    $hotel_id = $request->input('hotel_id'); 
    $adults = $request->input('adults');
    $children = $request->input('children');
    $booking_start_date = $request->input('booking_start_date');
    $booking_end_date = $request->input('booking_end_date');
    $day_count = $request->input('day_count');
    $sub_total = $request->input('sub_total');
    $total_amount = $request->input('total_amount');
    $order_number = $request->input('order_number');
    $promo_code = $request->input('promo_code');
    $transaction_id = $request->input('transaction_id');
    $order_number = $request->input('order_number');
    // Create the booking entry in the bookings table
    $booking_id = DB::table('bookings')->insertGetId([
        'hotel_id' => $hotel_id,
        'user_id' => $user_id,
        'adults' => $adults,
        'children' => $children,
        'day_count' => $day_count,
        'sub_total_amount' => $sub_total,
        'total_amount' => $total_amount,
        'promo_code' => $promo_code,
        'transaction_id' => $transaction_id,
        'order_number' => $order_number,
        'booking_start_date' => $booking_start_date,
        'booking_end_date' => $booking_end_date,
        'booking_status' => 'Pending',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    if ($booking_id) {

        return $this->sendResponse([], 'Booking added successfully.', [], [], 200);
    } else {
        return $this->sendError([], 'Failed to add booking.', [], [], 200);
    }
}

public function get_booking_list(Request $request)
{
    if (!Auth::guard('api')->check()) {
        return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 401);
    }

    // Validate the 'type' input (upcoming or previous)
    $validator = Validator::make($request->all(), [
        'type' => 'required|in:upcoming,previous',
    ]);

    if ($validator->fails()) {
        return $this->sendError($result = [], $message = $validator->errors()->first(), $notification = [], $error = [], $respose_code = 400);
    }

    // Retrieve the authenticated user
    $user = Auth::guard('api')->user();
    
    // Get current date and time
    // $currentDate = now();
    $currentDate = now()->toDateString();

    // Determine whether to fetch upcoming or previous bookings
    $bookingQuery = DB::table('bookings')
        ->join('hotel_list', 'bookings.hotel_id', '=', 'hotel_list.id')
        ->where('bookings.user_id', $user->id)
        ->where('hotel_list.status', 'ACTIVE') // Only active hotels
        ->select(
            'bookings.id as booking_id',
            'bookings.adults',
            'bookings.children',
            'bookings.booking_start_date',
             'bookings.booking_end_date',
            'hotel_list.id as hotel_id',
            'hotel_list.title as hotel_name',
            'hotel_list.description as hotel_description',
            'hotel_list.image as hotel_main_image'
        );

    // Apply filter based on 'type'
    if ($request->type === 'upcoming') {
        // For upcoming bookings: booking start date should be in the future
        $bookingQuery->where('bookings.booking_end_date', '>=', $currentDate);
    } else {
        // For previous bookings: booking start date should be in the past
        $bookingQuery->where('bookings.booking_end_date', '<', $currentDate);
        
    }

    // Get the results
    $bookings = $bookingQuery->get();

    foreach ($bookings as $booking) {
        // Set hotel main image path
        $booking->hotel_main_image = url("/") . "/public/uploads/hotel/" . $booking->hotel_main_image;

        // Retrieve hotel gallery images
        $galleryImages = DB::table('hotel_gallery')
            ->where('hotel_id', $booking->hotel_id)
            ->get();

        foreach ($galleryImages as $galleryImage) {
            $galleryImage->image = url("/") . "/public/uploads/hotel_gallery/" . $galleryImage->image;
        }

        $booking->hotel_gallery = $galleryImages;

        // Retrieve average rating for the hotel
        $averageRating = DB::table('reviews')
            ->where('hotel_id', $booking->hotel_id)
            ->avg('review_rating');
        $booking->average_rating = $averageRating ? round($averageRating, 2) : 0;

        // Retrieve booking dates from `booking_dates` table
       
    }

    if ($bookings->isNotEmpty()) {
        return $this->sendResponse($result = $bookings, $message = 'Booking list retrieved successfully.', $notification = [], $error = [], $respose_code = 200);
    } else {
        return $this->sendError($result = [], $message = 'No bookings found.', $notification = [], $error = [], $respose_code = 200);
    }
}


// public function get_booking_list(Request $request)
// {
//     if (!Auth::guard('api')->check()) {
//         return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 401);
//     }

//     // Retrieve the authenticated user
//     $user = Auth::guard('api')->user();

//     // Get the list of bookings for the current user
//     $bookings = DB::table('bookings')
//         ->join('hotel_list', 'bookings.hotel_id', '=', 'hotel_list.id')
//         ->where('bookings.user_id', $user->id)
//         ->where('hotel_list.status', 'ACTIVE')  // Only active hotels
//         ->select(
//             'bookings.id as booking_id',
//             'bookings.adults',
//             'bookings.children',
//             'bookings.created_at as booking_date',
//             'hotel_list.id as hotel_id',
//             'hotel_list.title as hotel_name',
//             'hotel_list.description as hotel_description',
//             'hotel_list.image as hotel_main_image'
//         )
//         ->get();

//     foreach ($bookings as $booking) {
//         // Set hotel main image path
//         $booking->hotel_main_image = url("/") . "/public/uploads/hotel/" . $booking->hotel_main_image;

//         // Retrieve hotel gallery images
//         $galleryImages = DB::table('hotel_gallery')
//             ->where('hotel_id', $booking->hotel_id)
//             ->get();

//         foreach ($galleryImages as $galleryImage) {
//             $galleryImage->image = url("/") . "/public/uploads/hotel_gallery/" . $galleryImage->image;
//         }

//         $booking->hotel_gallery = $galleryImages;

//         // Retrieve average rating for the hotel
//         $averageRating = DB::table('reviews')
//             ->where('hotel_id', $booking->hotel_id)
//             ->avg('review_rating');
//         $booking->average_rating = $averageRating ? round($averageRating, 2) : 0;

//         // Retrieve booking dates from `booking_dates` table
//         // $bookingDates = DB::table('booking_dates')
//         //     ->where('booking_id', $booking->booking_id)
//         //     ->pluck('booking_date');

//         // $booking->booking_dates = $bookingDates;
        
//           $isSaved = DB::table('saved')
//             ->where('user_id', $user)  // Check with the logged-in user's ID
//             ->where('hotel_id',$booking->hotel_id)  // Check with the current hotel's ID
//             ->exists();

//         // Add saved status to the hotel object
//         $booking->is_saved = $isSaved ? true : false;
//     }

//     if ($bookings->isNotEmpty()) {
//         return $this->sendResponse($result = $bookings, $message = 'Booking list retrieved successfully.', $notification = [], $error = [], $respose_code = 200);
//     } else {
//         return $this->sendError($result = [], $message = 'No bookings found.', $notification = [], $error = [], $respose_code = 200);
//     }
// }

 


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

 
    
}
