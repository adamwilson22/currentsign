<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Validator;
use Hash;

class HomeController extends Controller
{


    public function getHome(Request $request)
    {
        // Authenticating user based on the access token
        if (!Auth::guard('api')->check()) {
            return $this->sendError($result = [], $message = 'Unauthorized.', $notification = [], $error = [], $respose_code = 401);
        }


        $restaurants = DB::select("SELECT * FROM `restaurants` where `res_deleted_at` IS NULL and `res_admin_status` = 'ACTIVE' order by res_id limit 10");
        $banners = DB::select("SELECT * FROM `banners` where `ban_type`= 'HOME' and `ban_deleted_at` IS NULL and `ban_admin_status` = 'ACTIVE' order by ban_id limit 10");
        $categories = DB::select("SELECT * FROM `restaurants_categories` where `rescat_deleted_at` IS NULL and `rescat_admin_status` = 'ACTIVE' order by rescat_id limit 10");
        $restaurants_items = DB::select("SELECT * FROM `restaurants_items` where `reit_deleted_at` IS NULL and `reit_admin_status` = 'ACTIVE' order by reit_id limit 10");


        if(is_array($restaurants)){
        foreach($restaurants as $restaurantsIn){
            $restaurantsIn->res_image = url("/") . "/storage/app/restaurants/".  $restaurantsIn->res_image;
        }
        }
        if(is_array($banners)){
        foreach($banners as $bannersIn){
            $bannersIn->ban_image = url("/") . "/storage/app/banners/".  $bannersIn->ban_image;
        }
        }
        if(is_array($categories)){
        foreach($categories as $categoriesIn){
            $categoriesIn->rescat_image = url("/") . "/storage/app/categories/".  $categoriesIn->rescat_image;
        }
        }
        if(is_array($restaurants_items)){
        foreach($restaurants_items as $restaurants_itemsIn){
            $restaurants_itemsIn->reit_image = url("/") . "/storage/app/restaurants-items/".  $restaurants_itemsIn->reit_image;
        }
        }
        
        $result['banners']  =  $banners;
        $result['categories']  =  $categories;
        $result['popular_dishes']  = $restaurants_items;
        $result['top_rated_restaurants']  =  $restaurants;

        return $this->sendResponse($result = $result, $message = 'Home data.', $notification = [], $error = [], $respose_code = 200);
    }

    public function getPrivacyPolicy(Request $request)
    {
        $result = DB::select("SELECT * FROM `privacy_policies` where `pp_deleted_at` IS NULL");

        return $this->sendResponse($result = $result, $message = 'Privacy policy.', $notification = [], $error = [], $respose_code = 200);

    }
   
    public function getTermsAndConditions(Request $request)
    {
        $result = DB::select("SELECT * FROM `terms_and_conditions` where `tac_deleted_at` IS NULL");

        return $this->sendResponse($result = $result, $message = 'Term and conditions.', $notification = [], $error = [], $respose_code = 200);

    }
}
