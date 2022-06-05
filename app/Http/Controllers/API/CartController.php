<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\ProductService;
use App\Services\WishlistService;

use App\Http\Controllers\Controller as Controller;
use Exception;

use App\Http\Requests\Cart\AddProductToCartRequest;

class CartController extends Controller
{
    public function get_user_cart(Request $request){
        try{
            $user = auth()->user();
            $cart = CartService::check_cart_if_empty_return_cart($user->id);
            if($cart != "cart_is_empty"){
                $result = CartService::update_cart_prices($cart->id);
                $success["cart"] = CartService::get_user_cart($cart->id, $request->currency_id);
                return $this->sendResponse('', $success);
            }else{
                $success["cart"] = (object)[];
                return $this->sendResponse('', $success);
            }

        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_user_detailed_cart(Request $request){
        try{
            $user = auth()->user();
            $cart = CartService::check_cart_if_empty_return_cart($user->id);
            if($cart != "cart_is_empty"){
                $result = CartService::update_cart_prices($cart->id);
                $success = CartService::get_user_detailed_cart($cart->id, $request->language_id,  $request->currency_id);
                return $this->sendResponse('', $success);
            }
            else{
                $success["cart"] = (object)[];
                return $this->sendResponse('', $success);
            }

        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function add_product_to_cart(AddProductToCartRequest $request){
        try{
            $user = auth()->user();
            $product_can_be_added_to_cart = ProductService::check_if_product_can_be_added_to_cart($request->product_id, $request->quantity);
            if($product_can_be_added_to_cart){
                $success = CartService::add_product_to_cart($request->product_id, $request->quantity, $user);
                return $this->sendResponse(__('messages.added_product_to_cart'), []);
            }else{
                return $this->sendError(__('messages.add_to_cart_error'), 400);
            }

        }catch(Exception $e){
            return $this->sendError($e->getMessage(), 500);
        }
    }

    public function remove_product_from_cart(Request $request){
        try{
            $user = auth()->user();
            $cart = CartService::check_cart_if_empty_return_cart($user->id);
            if($cart != "cart_is_empty"){
                $success = CartService::remove_product_from_cart($request->product_id, $cart);
                if($success == "product_not_found")
                    return $this->sendError(__('messages.product_not_found'), 400);
                else
                    return $this->sendResponse(__('messages.removed_product_from_cart'), []);
            }
            else{
                return $this->sendError(__('messages.cart_is_empty'), 400);
            }

        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_user_cart_and_wishlist_count(Request $request){
        try{
            $user = auth()->user();
            $success = array();
            $cart = CartService::check_cart_if_empty_return_cart($user->id);
            if($cart != "cart_is_empty"){
                $cart = CartService::get_user_cart($cart->id);
                $success['cart_items_count'] = $cart->cart_items_count;

            }else{
                $success['cart_items_count'] = 0;
            }

            $wishlist = WishlistService::check_wishlist_if_empty_return_wishlist($user->id);
            if($wishlist != "wishlist_is_empty"){
                $wishlist = WishlistService::get_user_wishlist($wishlist->id);
                $success['wishlist_items_count'] = $wishlist->wishlist_items_count;

            }else{
                $success['wishlist_items_count'] = 0;

            }
            return $this->sendResponse('', $success);

        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

}
