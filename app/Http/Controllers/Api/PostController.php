<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use DB;
    
    
class PostController extends Controller
{
    
public function create_post(Request $request)
{
     if (!Auth::guard('api')->check()) {
        return $this->sendError(
            $result = null,
            $message = 'Unauthorized.',
            $notification = [],
            $error = [],
            $respose_code = 401
        );
    }
    
    $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image_1' => 'required',
        ]);

    if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = null, $message = @$validator_error[0], $notification = null, $error = $validator_error, $respose_code = 200);
        }

    $user = Auth::guard('api')->user();
    try{
        
    $data = $request->all();
    if ($request->hasFile('image_1')) {
        // Store the image in the public/uploads directory
        $imageName = time() . '_' . $request->file('image_1')->getClientOriginalName();
        $request->file('image_1')->move(public_path('uploads'), $imageName);
        // Add the image path to the request data
        $data['image_1'] = 'uploads/' . $imageName;
    }
    
    if ($request->hasFile('image_2')) {
        // Store the image in the public/uploads directory
        $imageName = time() . '_' . $request->file('image_2')->getClientOriginalName();
        $request->file('image_2')->move(public_path('uploads'), $imageName);
        // Add the image path to the request data
        $data['image_2'] = 'uploads/' . $imageName;
    }
    // if($data['user_list']){
    //     $data['is_private'] = true;
    // }
    
    if($user->user_type == 'ADMIN'){
        $data['is_private'] = 'FALSE';
    }else{
        $data['is_private'] = 'TRUE';
    }
    
       $data['user_id'] = $user->id;
       
       $postId = DB::table('posts')->insertGetId($data);
       $notify_arr = [
           'notification_text' => 'New Post',
           'notification_message' => $data['title'],
           'post_id' => $postId
           ];
       $notify_id = DB::table('notifications')->insertGetId($notify_arr);
       $users = DB::table('users')->get();
       foreach($users as $icon){
           $notify_user_arr = [
           'user_id' => $icon->id,
           'notification_id' => $notify_id,
           'notification_id' => $notify_id,
           ];
          DB::table('user_notifications')->insertGetId($notify_user_arr);
       }
       
       
       return $this->sendResponse($result = null, $message = 'successfully.', $notification = null, $error = null, $respose_code = 200);
    }catch (\Exception $e) {
        return $this->sendError(
            $result = null,
            $message = 'An error occurred .'.$e,
            $notification = [],
            $error = [],
            $respose_code = 500
        );
    }
}

    public function follow(Request $request)
    {
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
    
        $followerId = $user->id;
        $followingId = $request->user_id;

        if ($followerId == $followingId) {
            return $this->sendError(
            $result = null,
            $message = 'You cant follow yourself .',
            $notification = [],
            $error = [],
            $respose_code = 200
        );
        
        }

        $exists = DB::table('follows')
            ->where('follower_id', $followerId)
            ->where('following_id', $followingId)
            ->exists();

        if ($exists) {
            DB::table('follows')
            ->where('follower_id', $followerId)
            ->where('following_id', $followingId)->delete();
           return $this->sendResponse(
            $result = null,
            $message = 'Unfollow successfully .',
            $notification = [],
            $error = [],
            $respose_code = 200
        );
        }

        // Create a pending follow request
        DB::table('follows')->insert([
            'follower_id' => $followerId,
            'following_id' => $followingId,
            'status' => 'accepted',
        ]);

      return $this->sendResponse($result = null, $message = 'Follow request sent successfully.', $notification = null, $error = null, $respose_code = 200);
    }
    
    
    



public function getNofifications(Request $request){
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
    try{
        
     $user_notifications = DB::table('user_notifications')
    ->where('user_id', $user->id)
    ->orderBy('created_at', 'desc') // Sort by created_at in descending order
    ->get();

      foreach($user_notifications as &$user_notification){
          $user_notification->notification_data = DB::table('notifications')->where('notification_id', $user_notification->notification_id)->first();
      }
        
      return $this->sendResponse($result = $user_notifications, $message = 'successfully.', $notification = null, $error = null, $respose_code = 200);  
    }catch (\Exception $e) {
        return $this->sendError(
            $result = null,
            $message = 'An error occurred .'.$e,
            $notification = [],
            $error = [],
            $respose_code = 500
        );
    }
    
}


public function deleteNotification(Request $request){
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
   
   $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

    if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = null, $message = @$validator_error[0], $notification = null, $error = $validator_error, $respose_code = 200);
        }
        
    try{ 
        
         DB::table('user_notifications')->where('id', $request->id)->delete();
         return $this->sendResponse($result = null, $message = 'successfully.', $notification = null, $error = null, $respose_code = 200); 
        }catch (\Exception $e) {
        return $this->sendError(
            $result = null,
            $message = 'An error occurred .'.$e,
            $notification = [],
            $error = [],
            $respose_code = 500
        );
    }
    
}


public function get_posts(Request $request)
{
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
    try{
        if($user->user_type == 'ADMIN'){
            $posts = DB::table('posts')->where('is_private', 'FALSE')->where('category_id', $request->category_id)->get();
        }else{
              $posts = DB::table('posts')->where('is_private', 'FALSE')->where('category_id', $request->category_id)->get();
        }
       
       $res =[];
       foreach($posts as &$post){
    // Updating image paths
         if($post->image_1){
               $post->image_1 = url('/').'/public/'.$post->image_1;
           }
           if($post->image_2){
               $post->image_2 = url('/').'/public/'.$post->image_2;
           }

    // Fetching vote data and calculating percentages
    $results = DB::table('votes')
    ->select(
        'post_id',
        DB::raw('COUNT(DISTINCT user_id) AS user_count'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_1 = "YES", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_1_percent_yes'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_2 = "YES", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_2_percent_yes'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_1 = "NO", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_1_percent_no'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_2 = "NO", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_2_percent_no')
    )
    ->where('post_id', $post->id)
    ->groupBy('post_id')
    ->first();

// Safely assign average percentages, defaulting to 0 if null
$post->avg_option_1_percent_yes = $results ? $results->avg_option_1_percent_yes : '0';
$post->avg_option_2_percent_yes = $results ? $results->avg_option_2_percent_yes : '0';
$post->avg_option_2_percent_no = $results ? $results->avg_option_2_percent_no : '0';
$post->avg_option_1_percent_no = $results ? $results->avg_option_1_percent_no : '0';
$post->user_vote_count = $results ? $results->user_count : 0;

// if($post->user_id != $user->id){
    if($post->visible_days){
            $expirationDate = \Carbon\Carbon::parse($post->created_at)->addDays($post->visible_days);
        if (now()->lte($expirationDate)) {
            $res[] = $post; // Store the post in the result array
        }
  }
//}


}



       
       return $this->sendResponse($result = $res, $message = 'successfully.', $notification = null, $error = null, $respose_code = 200);
    }catch (\Exception $e) {
        return $this->sendError(
            $result = null,
            $message = 'An error occurred .'.$e,
            $notification = [],
            $error = [],
            $respose_code = 500
        );
    }
}

public function get_postbyid(Request $request)
{
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
    try{
       $posts = DB::table('posts')->where('id', $request->post_id)->get();
       $res =[];
       foreach($posts as &$post){
    // Updating image paths
         if($post->image_1){
               $post->image_1 = url('/').'/public/'.$post->image_1;
           }
           if($post->image_2){
               $post->image_2 = url('/').'/public/'.$post->image_2;
           }

    // Fetching vote data and calculating percentages
    $results = DB::table('votes')
    ->select(
        'post_id',
        DB::raw('COUNT(DISTINCT user_id) AS user_count'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_1 = "YES", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_1_percent_yes'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_2 = "YES", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_2_percent_yes'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_1 = "NO", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_1_percent_no'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_2 = "NO", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_2_percent_no')
    )
    ->where('post_id', $post->id)
    ->groupBy('post_id')
    ->first();

// Safely assign average percentages, defaulting to 0 if null
$post->avg_option_1_percent_yes = $results ? $results->avg_option_1_percent_yes : '0';
$post->avg_option_2_percent_yes = $results ? $results->avg_option_2_percent_yes : '0';
$post->avg_option_2_percent_no = $results ? $results->avg_option_2_percent_no : '0';
$post->avg_option_1_percent_no = $results ? $results->avg_option_1_percent_no : '0';
$post->user_vote_count = $results ? $results->user_count : 0;

// if($post->user_id != $user->id){
//     if($post->visible_days){
//             $expirationDate = \Carbon\Carbon::parse($post->created_at)->addDays($post->visible_days);
//         if (now()->lte($expirationDate)) {
//             $res[] = $post; // Store the post in the result array
//         }
//   }
// }


}



       
       return $this->sendResponse($result = $posts, $message = 'successfully.', $notification = null, $error = null, $respose_code = 200);
    }catch (\Exception $e) {
        return $this->sendError(
            $result = null,
            $message = 'An error occurred .'.$e,
            $notification = [],
            $error = [],
            $respose_code = 500
        );
    }
}

public function get_posts_private(Request $request)
{
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
    try{
        
        if($user->user_type == 'ADMIN'){
            $posts = DB::table('posts')->get();
        }else{
              $followedUserIds = DB::table('follows')->where('follower_id', $user->id)->where('status', 'accepted')->pluck('following_id');
              $followedUserIds->push($user->id);
              $posts = DB::table('posts')->where('is_private', 'TRUE')->whereIn('user_id', $followedUserIds)->get();
        }
        
       $post_pvt = [];
       foreach($posts as &$post){
           if($post->image_1){
               $post->image_1 = url('/').'/public/'.$post->image_1;
           }
           if($post->image_2){
               $post->image_2 = url('/').'/public/'.$post->image_2;
           }
           $results = DB::table('votes')
    ->select(
        'post_id',
        DB::raw('COUNT(DISTINCT user_id) AS user_count'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_1 = "YES", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_1_percent_yes'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_2 = "YES", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_2_percent_yes'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_1 = "NO", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_1_percent_no'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_2 = "NO", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_2_percent_no')
    )
    ->where('post_id', $post->id)
    ->groupBy('post_id')
    ->first();

// Safely assign average percentages, defaulting to 0 if null
$post->avg_option_1_percent_yes = $results ? $results->avg_option_1_percent_yes : '0';
$post->avg_option_2_percent_yes = $results ? $results->avg_option_2_percent_yes : '0';
$post->avg_option_2_percent_no = $results ? $results->avg_option_2_percent_no : '0';
$post->avg_option_1_percent_no = $results ? $results->avg_option_1_percent_no : '0';
$post->user_vote_count = $results ? $results->user_count : 0;

   // $user_arr = explode(",",$post->user_list);
     if($post->visible_days){
      $expirationDate = \Carbon\Carbon::parse($post->created_at)->addDays($post->visible_days);  
      if (now()->lte($expirationDate)) {
 //   if (in_array($user->id, $user_arr)){
        $post_pvt[] = $post;
  //  }
    
     }
     }

}
       
       return $this->sendResponse($result = $post_pvt, $message = 'successfully.', $notification = null, $error = null, $respose_code = 200);
    }catch (\Exception $e) {
        return $this->sendError(
            $result = null,
            $message = 'An error occurred .',
            $notification = [],
            $error = [],
            $respose_code = 500
        );
    }
}

public function my_posts(Request $request)
{
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
    try{
       $posts = DB::table('posts')->where('user_id', $user->id)->get();
       foreach($posts as $post){
           if($post->image_1){
               $post->image_1 = url('/').'/public/'.$post->image_1;
           }
           if($post->image_2){
               $post->image_2 = url('/').'/public/'.$post->image_2;
           }
           
               $results = DB::table('votes')
    ->select(
        'post_id',
        DB::raw('COUNT(DISTINCT user_id) AS user_count'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_1 = "YES", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_1_percent_yes'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_2 = "YES", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_2_percent_yes'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_1 = "NO", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_1_percent_no'),
        DB::raw('IF(COUNT(DISTINCT user_id) > 0, SUM(COALESCE(vote_option_2 = "NO", 0)) / COUNT(DISTINCT user_id) * 100, 0) AS avg_option_2_percent_no')
    )
    ->where('post_id', $post->id)
    ->groupBy('post_id')
    ->first();

// Safely assign average percentages, defaulting to 0 if null
$post->avg_option_1_percent_yes = $results ? $results->avg_option_1_percent_yes : '0';
$post->avg_option_2_percent_yes = $results ? $results->avg_option_2_percent_yes : '0';
$post->avg_option_2_percent_no = $results ? $results->avg_option_2_percent_no : '0';
$post->avg_option_1_percent_no = $results ? $results->avg_option_1_percent_no : '0';
$post->user_vote_count = $results ? $results->user_count : 0;

       }
       
       return $this->sendResponse($result = $posts, $message = 'successfully.', $notification = null, $error = null, $respose_code = 200);
    }catch (\Exception $e) {
        return $this->sendError(
            $result = null,
            $message = 'An error occurred .',
            $notification = [],
            $error = [],
            $respose_code = 500
        );
    }
}

public function vote(Request $request)
{
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
    try{
        $validator = Validator::make($request->all(), [
            'post_id' => 'required',
        ]);

      if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = null, $message = @$validator_error[0], $notification = null, $error = $validator_error, $respose_code = 200);
        }
        
       $data = $request->all();
       $data['user_id'] = $user->id;
       $res = DB::table('votes')->where('user_id', $user->id)->where('post_id', $request->post_id)->first();
       if($res){
           return $this->sendError(
            $result = null,
            $message = 'Allready Voted.',
            $notification = [],
            $error = [],
            $respose_code = 200
        );
          // DB::table('votes')->where('user_id', $user->id)->where('post_id', $request->post_id)->update($data);
       }else{
            DB::table('votes')->insertGetId($data);
       }
       
      
      
       
    return $this->sendResponse($result = null, $message = 'successfully.', $notification = null, $error = null, $respose_code = 200);
    }catch (\Exception $e) {
        return $this->sendError(
            $result = null,
            $message = 'An error occurred .'.$e,
            $notification = [],
            $error = [],
            $respose_code = 500
        );
    }
}


public function getvoterlist(Request $request){
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
     try{
        $validator = Validator::make($request->all(), [
            'post_id' => 'required',
        ]);
         if ($validator->fails()) {
            $validator_error = array_values($validator->errors()->toArray());
            $validator_error = array_merge(...$validator_error);
            return $this->sendError($result = null, $message = @$validator_error[0], $notification = null, $error = $validator_error, $respose_code = 200);
        }
        
       $res_yes = DB::table('votes')->where('post_id', $request->post_id)->get();
       foreach($res_yes as &$yes){
             $user =  DB::table('users')->where('id', $yes->user_id)->first();
            if($user->image){
            $user->image = url('/').'/storage/app/public/'.$user->image;
            }
         $yes->user_data = $user;
       }
      
      
    return $this->sendResponse($result = $res_yes, $message = 'successfully.', $notification = null, $error = null, $respose_code = 200);
     }catch (\Exception $e) {
        return $this->sendError(
            $result = null,
            $message = 'An error occurred .'.$e,
            $notification = [],
            $error = [],
            $respose_code = 500
        );
    }  
}





}