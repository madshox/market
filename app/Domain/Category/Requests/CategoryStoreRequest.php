<?php

namespace Domain\Category\Requests;

use Illuminate\Http\Response;
use App\Rules\MaxSubcategories;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CategoryStoreRequest extends FormRequest
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
        $parentId = (int) $this->post('parent_id');
        $locales = config('app.locales');
        return [
            'name' => 'required|array|' . 'required_array_keys:' . implode(',', $locales),
            'icon' => 'sometimes|max:2048',
            'parent_id' => [
                'sometimes',
                'exists:categories,id',
                new MaxSubcategories(10, $parentId),
            ],
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
