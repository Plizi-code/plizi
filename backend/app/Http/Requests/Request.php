<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

abstract class Request extends FormRequest
{
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Неверный формат данных',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
