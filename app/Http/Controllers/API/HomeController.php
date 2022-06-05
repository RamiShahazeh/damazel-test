<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Services\CurrencyService;
use App\Services\LocationService;

use App\Services\ProductService;
use App\Services\OfferService;
use App\Services\SocialMediaService;
use App\Services\SliderService;
use App\Http\Controllers\Controller as Controller;
use App\Http\Requests\Subcategory\SubcategoryProductGetRequest;
use App\Http\Requests\Slider\SliderGetWithTypeRequest;
use App\Http\Requests\Category\CategoryGetRequest;

use Exception;

use App\Http\Requests\Home\HomeRequest;


class HomeController extends Controller
{

    public function get_categories_with_language(Request $request)
    {
        try{
            $success["categories"] = CategoryService::get_categories_with_language($request);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_subcategories_with_language(CategoryGetRequest $request)
    {
        try{
            $success["subcategories"] = CategoryService::get_subcategories_with_language($request->category_id,$request->language_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }
    
    public function get_categories_subcategories_with_language(Request $request)
    {
        try{
            $success["categories"] = CategoryService::get_categories_subcategories_with_language($request->language_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_subcategories_products_with_language(SubcategoryProductGetRequest $request)
    {
        try{
            $success = ProductService::get_subcategories_products_with_language($request, $request->language_id, $request->currency_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }
    
    public function get_mobile_json(Request $request){
        
        try{
            $success = json_decode(file_get_contents(public_path() . "/mobile_json.json"), true);

            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_sliders_with_language(SliderGetWithTypeRequest $request)
    {
        try{
            $success= SliderService::get_sliders_with_language($request);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_currencies_with_language(Request $request)
    {
        try{
            $success["currencies"] = CurrencyService::get_currencies_with_language($request->language_id);
            return $this->sendResponse('', $success);
        }
        catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_active_social_media(Request $request)
    {
        try{
            $success["social_media"] = SocialMediaService::get_active_social_media();
            return $this->sendResponse('', $success);
        }
        catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }
    
    public function get_distance(Request $request)
    {
        try{
            $success = LocationService::getDistance($request->latFrom, $request->lngFrom, $request->latTo, $request->lngTo, 'metric');
            return $this->sendResponse('', $success);
        }
        catch(Exception $e){
            return $this->sendError($e->getMessage(), 500);
        }
    }

    

}
