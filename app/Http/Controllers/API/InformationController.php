<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\InformationService;
use App\Services\SocialMediaService;
use App\Http\Controllers\Controller as Controller;
use Exception;

class InformationController extends Controller
{

    public function get_about_us_with_language(Request $request)
    {
        try{
            $success["about_us"]= InformationService::get_about_us_with_language($request->language_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError($e->getMessage(), 500);
        }
    }

    public function get_privacy_with_language(Request $request)
    {
        try{
            $success = InformationService::get_privacy_with_language($request->language_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }
    
    public function get_conditions_with_language(Request $request)
    {
        try{
            $success = InformationService::get_conditions_with_language($request->language_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function get_company_info_with_language(Request $request)
    {
        try{
            $success = InformationService::get_company_info_with_language($request->language_id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }




}
