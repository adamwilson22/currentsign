<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use DB;
    
    
class ChatController extends Controller
{
public function post_chat(Request $request)
{
    if (!Auth::guard('api')->check()) {
        return $this->sendError([], 'Unauthorized.', [], [], 200);
    }

    $sender_id = Auth::guard('api')->id();

    $validator = Validator::make($request->all(), [
        'chat_receiver_id' => 'required|numeric',
        'chat_message' => 'required',
        'chat_type' => 'required|in:TEXT,IMAGE,AUDIO,VIDEO,PDF',
    ]);

    if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return $this->sendError([], $errors[0], [], $errors, 200);
    }

    $chat_message = $request->input('chat_message');
    
    if ($request->input('chat_type') == "IMAGE" || 
        $request->input('chat_type') == "AUDIO" || 
        $request->input('chat_type') == "VIDEO" || 
        $request->input('chat_type') == "PDF") {
        
        if ($request->hasFile('chat_message')) {
            $file = $request->file('chat_message');
            $chat_message = rand(1111, 9999) . "_" . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/chats'), $chat_message);
        }
    }
    
    $data = [
        'chat_sender_id' => $sender_id,
        'chat_receiver_id' => $request->input('chat_receiver_id'),
        'chat_message' => $chat_message,
        'chat_type' => $request->input('chat_type'),
        'chat_created_at' => now(),
        'chat_updated_at' => now(),
    ];
    
    // Insert the chat message into the database
    $inserted = DB::table('chats')->insert($data);

    if ($inserted) {
        return $this->sendResponse([], 'Message sent.', [], [], 200);
    } else {
        return $this->sendError([], 'Failed to send message.', [], [], 200);
    }
}

public function get_chat(Request $request)
{
    // Check if the user is authenticated
    if (!Auth::guard('api')->check()) {
        return $this->sendError([], 'Unauthorized.', [], [], 200);
    }

    // Get the authenticated user's ID
    $user_id = Auth::guard('api')->id();

    // Validate the request parameters
    $validator = Validator::make($request->all(), [
        'receiver_id' => 'required|numeric', // The receiver's user ID
    ]);

    // If validation fails, return an error response
    if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return $this->sendError([], $errors[0], [], $errors, 200);
    }

    // Retrieve chats between the authenticated user and the specified receiver
    $receiver_id = $request->input('receiver_id');
    $chats = DB::table('chats')
                ->where(function ($query) use ($user_id, $receiver_id) {
                    $query->where('chat_sender_id', $user_id)
                          ->where('chat_receiver_id', $receiver_id);
                })
                ->orWhere(function ($query) use ($user_id, $receiver_id) {
                    $query->where('chat_sender_id', $receiver_id)
                          ->where('chat_receiver_id', $user_id);
                })
                ->orderBy('chat_created_at')
                ->get();

    // If no chats found, return a not found response
    if ($chats->isEmpty()) {
        return $this->sendError([], 'No chats found.', [], [], 200);
    }

    // Prepare the chats response with proper message format
    foreach ($chats as $chat) {
        if ($chat->chat_type != 'TEXT') {
            // For non-text messages, prepend base URL to the message
            $chat->chat_message = url('/public/uploads/chats/' . $chat->chat_message);
        }
    }

    // Return the chats as a success response
    return $this->sendResponse($chats, 'Chats retrieved successfully.', [], [], 200);
}

public function get_last_chats(Request $request)
{
    // Check if the user is authenticated
    if (!Auth::guard('api')->check()) {
        return $this->sendError([], 'Unauthorized.', [], [], 401);
    }

    // Get the authenticated user's ID
    $user_id = Auth::guard('api')->id();

    // Disable ONLY_FULL_GROUP_BY mode
    DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

    // Execute the raw SQL query
    $last_chats = DB::select("
        SELECT 
            c.chat_sender_id,
            c.chat_receiver_id,
            c.chat_message,
            MAX(c.chat_created_at) AS chat_created_at,
            IF(c.chat_sender_id = ?, c.chat_receiver_id, c.chat_sender_id) AS other_user_id,
            u.full_name AS other_user_full_name
        FROM 
            chats c
        JOIN 
            users u ON IF(c.chat_sender_id = ?, c.chat_receiver_id, c.chat_sender_id) = u.id
        WHERE 
            c.chat_type = 'TEXT' 
            AND (c.chat_sender_id = ? OR c.chat_receiver_id = ?)
        GROUP BY 
            other_user_id
        ORDER BY 
            chat_created_at DESC
    ", [$user_id, $user_id, $user_id, $user_id]);

    // If no last chats found, return a not found response
    if (empty($last_chats)) {
        return $this->sendError([], 'No last chats found.', [], [], 200);
    }

    // Return the last chats as a success response
    return $this->sendResponse($last_chats, 'Last chats retrieved successfully.', [], [], 200);
}

 
}

