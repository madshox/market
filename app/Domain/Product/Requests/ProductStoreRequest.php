<?php

namespace Domain\Product\Requests;

use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ProductStoreRequest extends FormRequest
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
        $locales = config('app.locales');
        return [
            'name' => 'required|array|' . 'required_array_keys:' . implode(',', $locales),
            'price' => 'required|numeric',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|array|' . 'required_array_keys:' . implode(',', $locales),
            'in_stock' => 'sometimes|boolean',
            'category_id' => 'required|exists:categories,id',
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
