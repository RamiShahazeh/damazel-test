<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\OfferService;
use App\Services\UserService;
use App\Services\OrderService;
use App\Services\StripeService;
use App\Services\ProductService;

use App\Http\Controllers\Controller as Controller;
use Exception;
use App\Http\Requests\Order\UserCheckoutRequest;
use App\Http\Requests\Order\GetOrderRequest;
class OrderController extends Controller
{
    public function user_checkout(UserCheckoutRequest $request)
    {
        try{
            $user = auth()->user(); 
            $cart = CartService::check_cart_if_empty_return_cart($user->id);
            if($cart != "cart_is_empty"){
                UserService::update_user_address_by_user($request, $user->id);
                $stripe_customer = StripeService::update_stripe_customer($user);
                $order_code = OrderService::get_new_order_code();
                $cart_products = CartService::get_cart_products_for_order($cart->id);
                $totals = CartService::get_cart_totals_for_order($cart->id);
                $payment_succeed = StripeService::check_payment($user->id, $request->payment_id);
                if($payment_succeed){
                    $success = OrderService::create_order($request->payment_id, $user->id, $order_code, $totals->overall_total, $cart_products, $totals);
                    CartService::empty_user_cart($user->id);
                    foreach($cart_products["products"] as $product)
                        ProductService::substract_quantity_increase_purchase_count($product->id, $product->product_quantity);
                    
                    return $this->sendResponse(__('messages.order_submitted'), $success);
               }else
                   return $this->sendError(__('messages.payment_error'), 400);        
            }
            else
                return $this->sendError(__('messages.cart_is_empty'), 400);
           
        }catch(Exception $e){
            return $this->sendError($e->getmessage(), 500);
        }
    }

    public function get_payment_client_secret(Request $request){
        try{
            $user = auth()->user(); 
            $cart = CartService::check_cart_if_empty_return_cart($user->id);
            if($cart != "cart_is_empty"){
                $totals = CartService::get_cart_totals_for_order($cart->id);
                $success["payment"] = StripeService::create_new_payment($user, round($totals->overall_total, 2));
                return $this->sendResponse('', $success);
            }
            else
                return $this->sendError(__('messages.cart_is_empty'), 400);
           
        }catch(Exception $e){
            return $this->sendError($e->getMessage(), 500);
        }
    }

    public function get_user_orders(Request $request){
        $user = auth()->user(); 
        try{
            $success = OrderService::get_user_orders($request, $user->id, $request->currency_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_user_order_track_details(GetOrderRequest $request){
        $user = auth()->user();
        try{
            $result = OrderService::check_if_user_did_order($request->order_id, $user->id);
            if($result){
                $success["order"] = OrderService::get_user_order_track_details($request->order_id, $request->language_id, $request->currency_id);
                return $this->sendResponse('', $success);
            }else{
                return $this->sendError(__('messages.order_not_found'), 400);    
            }

        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    
    

}
