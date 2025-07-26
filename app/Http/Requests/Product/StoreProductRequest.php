<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0|max:99999',
            'price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0|max:100',
            'description' => 'required|string|max:1000',

            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',

            'attributes' => 'required|array',
            'attributes.*.attribute_id' => 'required|integer|exists:attributes,id|distinct',
            'attributes.*.value_ids' => 'required|array|min:1',
            'attributes.*.value_ids.*' => 'required|integer|exists:attribute_values,id',
        ];
    }
}
