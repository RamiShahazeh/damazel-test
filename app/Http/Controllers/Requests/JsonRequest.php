<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class JsonRequest extends FormRequest
{
    // to return the validation errors as json response
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
          response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
          ], 400)
        );
    }
}
