<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\JsonRequest;

class LoginRequest extends JsonRequest
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
            'email' => 'required|email|exists:users,email|max:255|min:1|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'required|string|max:255|min:8',
        ];
    }

}
