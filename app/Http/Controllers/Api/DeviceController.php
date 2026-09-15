<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Validator;

class DeviceController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string',
            'platform' => 'nullable|in:ios,android',
            'device_id' => 'nullable|string|max:191',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors()->first(), [], [], 422);
        }

        if (! Schema::hasTable('device_tokens')) {
            return $this->sendError(null, 'Device tokens table missing. Run migrations.', [], [], 500);
        }

        $userId = Auth::guard('api')->check() ? Auth::guard('api')->id() : null;

        DB::table('device_tokens')->updateOrInsert(
            ['fcm_token' => $request->fcm_token],
            [
                'user_id' => $userId,
                'platform' => $request->platform,
                'device_id' => $request->device_id,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return $this->sendResponse(['registered' => true], 'Device registered.');
    }
}
