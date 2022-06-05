<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\WishlistService;
use App\Http\Controllers\Controller as Controller;
use Exception;

class WishlistController extends Controller
{
    public function get_user_wishlist(Request $request){
        try{
            $user = auth()->user(); 
            $wishlist = WishlistService::check_wishlist_if_empty_return_wishlist($user->id);
            if($wishlist != "wishlist_is_empty"){
                $success["wishlist"] = WishlistService::get_user_wishlist($wishlist->id, $request->currency_id);
                return $this->sendResponse('', $success);
            }else{
                $success["wishlist"] = (object)[];
                return $this->sendResponse('', $success);
            }

        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_user_detailed_wishlist(Request $request){
        try{
            $user = auth()->user();
            $wishlist = WishlistService::check_wishlist_if_empty_return_wishlist($user->id); 
            if($wishlist != "wishlist_is_empty"){
                $success = WishlistService::get_user_detailed_wishlist($wishlist->id, $request->language_id,  $request->currency_id);
                return $this->sendResponse('', $success);
            }
            else{
                $success["wishlist"] = (object)[];
                return $this->sendResponse('', $success);
            }
           
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function add_product_to_wishlist(Request $request){
        try{
            $user = auth()->user(); 
            $success = WishlistService::add_product_to_wishlist($request, $user->id);
            if($success == "product_already_in_wishlist")
                return $this->sendError(__('messages.product_already_in_wishlist'), 400);
            else
                return $this->sendResponse(__('messages.added_product_to_wishlist'), $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function remove_product_from_wishlist(Request $request){
        try{ 
            $user = auth()->user();
            $wishlist = WishlistService::check_wishlist_if_empty_return_wishlist($user->id); 
            if($wishlist != "wishlist_is_empty"){
                $success = WishlistService::remove_product_from_wishlist($request->product_id, $wishlist);
                
                if($success == "product_not_found")
                    return $this->sendError(__('messages.product_not_found'), 400);
                else
                    return $this->sendResponse('', $success);
            }
            else{
                return $this->sendError(__('messages.wishlist_is_empty'), 400);
            }
           
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }  
    }
    
}
