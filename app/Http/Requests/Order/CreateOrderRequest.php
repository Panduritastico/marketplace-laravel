<?php

namespace App\Http\Requests\Order;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id'
            ],
            'products' => [
                'required',
                'array'
            ],
            'products.*.product_id' => [
                'required',
                'integer',
                'exists:products,id'
            ],
            'products.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required',
            'user_id.integer' => 'The user ID must be an integer',
            'user_id.exists' => 'The user does not exist',
            'products.required' => 'The products are required',
            'products.array' => 'The products must be an array',
            'products.*.product_id.required' => 'The product ID is required',
            'products.*.product_id.integer' => 'The product ID must be an integer',
            'products.*.product_id.exists' => 'The product does not exist',
            'products.*.quantity.required' => 'The quantity is required',
            'products.*.quantity.integer' => 'The quantity must be an integer',
            'products.*.quantity.min' => 'The quantity must be at least 1'
        ];
    }
}
