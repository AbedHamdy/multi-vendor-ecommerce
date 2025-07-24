<?php

namespace App\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeRequest extends FormRequest
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
            'attributes' => 'required|array|min:1',
            'attributes.*.name' => 'required|string|max:255',
            'attributes.*.multi_text_values' => 'required|array|min:1',
            'attributes.*.multi_text_values.*' => 'required|string|max:255',
        ];
    }
}
