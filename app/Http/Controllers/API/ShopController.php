<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\ShopService;
use App\Http\Controllers\Controller as Controller;
use Exception;
use App\Http\Requests\ShopProduct\ShopProductGetRequest;
use App\Http\Requests\Shop\ShopProductsGetRequest;

class ShopController extends Controller
{

    public function get_all_shops_with_language(Request $request)
    {
        try{
            $success = ShopService::get_all_shops_with_language($request, $request->language_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_shop_with_language(Request $request)
    {
        try{
            $success["shop"] = ShopService::get_shop_with_language($request->shop_id, $request->language_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    
    public function get_shop_products_with_language(ShopProductsGetRequest $request)
    {
        try{
            $success = ShopService::get_shop_products_with_language($request, $request->language_id, $request->currency_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_shop_product_with_language(ShopProductGetRequest $request)
    {
        try{
            $success = ShopService::get_shop_product_with_language($request, $request->language_id, $request->currency_id);
            if($success == "product_not_found")
                return $this->sendError(__("messages.product_not_found"), 400);
            else
                return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_similar_shop_products_with_language(Request $request)
    {
        try{
            $success = ShopService::get_similar_shop_products_with_language($request);
            if($success == "product_not_found")
                return $this->sendError(__("messages.product_not_found"), 400);
            else
                return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }
    

}
