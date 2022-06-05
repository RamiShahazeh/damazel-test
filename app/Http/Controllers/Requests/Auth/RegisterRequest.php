<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\JsonRequest;

class RegisterRequest extends JsonRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255|min:5',
            'last_name' => 'required|string|max:255|min:5',
            'email' => 'required|email|unique:users|max:255|min:3|regex:/(.+)@(.+)\.(.+)/i',
            'profile_image' => 'nullable|image|mimes:jpeg,bmp,svg,png|max:10000',
            'password' => 'required|string|max:255|min:8|confirmed',
        ];
    }

}
