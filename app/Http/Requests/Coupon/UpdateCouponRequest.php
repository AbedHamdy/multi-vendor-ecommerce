<?php

namespace App\Http\Requests\Coupon;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
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
            'code' => ['required','string','max:50',Rule::unique('coupons', 'code')->ignore($this->id),
            ],
            'type' => 'required|in:general,welcome,loyalty,event',
            'discount_type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0.01',
            'usage_limit' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date|after_or_equal:now',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ];
    }
}
