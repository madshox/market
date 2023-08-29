<?php

namespace App\Http\Requests;

use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class FindOneRequest extends FormRequest
{
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
            'id' => 'required|numeric',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new Response([
            'message' => $validator->errors()->first()
        ], 422);
        throw new ValidationException($validator, $response);
    }
}
