<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Exception;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        
        try{
            if(isset($request->email)){
                $credentials = $request->only(['email', 'password']);
                $user = User::where('email', $request->email)->first();
            }
            else{
                $credentials = $request->only(['phone', 'password']);
                $user = User::where('phone', $request->phone)->first();
            }

            $user_role = $user->role()->first();
            if($user != null){
                if($user_role != null)
                    if($user_role->role_type == 'admin' || $user_role->role_type == 'super-admin' || $user_role->role_type == 'viewer')
                        return $this->sendError(__('auth.wrong_credentials'), 401);
            }

            if(auth()->attempt($credentials)){
                $user = auth()->user();
                if($user->is_verified){
                    $success['token'] = $user->createToken('MyApp',['customer'])->accessToken;
                    $success['user'] = $user;
                    $success['admin_role'] = 'customer';
                    return $this->sendResponse(__('auth.login_success'),$success);
                }else{
                    return $this->sendError(__('auth.user_not_verified'), 401);
                }
            }
            else {
                return $this->sendError(__('auth.wrong_credentials'), 401);
            }
        }catch(Exception $e){
            return $this->sendError(__('messages.get_data_error'), 500);
        }
    }

    public function register(RegisterRequest $request){
        try{
            $firebase_checking_user_flag = FirebaseService::validate_user_using_uid($request->id_token, $request->user_uid);
            if($firebase_checking_user_flag){
                $user = User::create([
                    'full_name' => $request->full_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'fcm_token' => isset($request->fcm_token) ? $request->fcm_token : NULL,
                    'user_uid' => $request->user_uid,
                    'is_verified' => 1
                ]);

                $user->role_type = 'customer';
                $success['token'] = $user->createToken('MyApp',['customer'])->accessToken;
                $success['user'] = $user;

                return $this->sendResponse(__('auth.login_success'),$success);
            }else{
                return $this->sendError(__('auth.unauthorized'), 403);
            }

        }catch(Exception $e){
            return $this->sendError($e->getMessage(), 500);
        }
    }

    public function unauthorize(){
        return $this->sendError(__('auth.unauthorized'), 403);
    }

    public function logout(){
        auth()->user()->tokens->each(function($token, $key) {
            $token->delete();
        });
        return $this->sendResponse(__('auth.logout_success'),[]);
    }

}
