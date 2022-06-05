<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller as Controller;
use Exception;

use App\Http\Requests\Account\AccountUpdateByUserRequest;
use App\Http\Requests\Account\AccountUpdatePasswordByUserRequest;
use App\Http\Requests\User\UpdateAddressRequest;

class UserController extends Controller
{

    public function get_user(){
        try{
            $user = auth()->user(); 
            $success['user'] = UserService::get_user_by_user($user->id);
            return $this->sendResponse('', $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function update_user_info_by_user(AccountUpdateByUserRequest $request)
    {
        try{
            $user = auth()->user(); 
            $success = UserService::update_user_info_by_user($request, $user->id);
            return $this->sendResponse(__('messages.updated_successfully'), $success);
        }catch(Exception $e){
            return $this->sendError($e->getMessage(), 500);
        }
    }

    public function update_user_password_by_user(AccountUpdatePasswordByUserRequest $request){
        try{
            $user = auth()->user(); 
            $success = UserService::update_user_password_by_user($request, $user->id);
            if($success == "wrong_old_password")
                return $this->sendError(__('messages.wrong_old_password'), 400);
            else
                return $this->sendResponse(__('messages.updated_successfully'), $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.update_error'), 500);
        }
    }

    public function update_user_address_by_user(UpdateAddressRequest $request){
        try{
            $user = auth()->user(); 
            $success = UserService::update_user_address_by_user($request, $user->id);
            return $this->sendResponse(__('messages.updated_successfully'), $success);
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }
}
