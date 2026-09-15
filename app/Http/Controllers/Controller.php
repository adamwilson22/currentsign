<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function sendResponse($result = [], $message = "" , $notification = [], $error = [] , $respose_code = 200)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
            'notification' => $notification,
            'error' => $error,
            'status' => '1',

        ];

        return response()->json($response, $respose_code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($result = [], $message = "" , $notification = [], $error = [] , $respose_code = 200)
    {
    	$response = [
            'success' => false,
            'data'    => $result,
            'message' => $message,
            'notification' => $notification,
            'error' => $error,
            'status' => '0',

        ];

        return response()->json($response, $respose_code);
    }

    /**
     * Strip leading public/ and build a browser-safe absolute URL for uploaded files.
     */
    protected function publicAssetUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');
        if (str_starts_with($normalized, 'public/')) {
            $normalized = substr($normalized, strlen('public/'));
        }

        return asset($normalized);
    }

    protected function normalizePublicPath(?string $path): string
    {
        if ($path === null || $path === '') {
            return '';
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');
        if (str_starts_with($normalized, 'public/')) {
            $normalized = substr($normalized, strlen('public/'));
        }

        return $normalized;
    }
    
}


