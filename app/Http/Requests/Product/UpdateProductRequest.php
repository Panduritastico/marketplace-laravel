<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => [
                'string',
                'max:255'
            ],
            'price' => [
                'numeric',
                'min:0.1'
            ],
            'stock' => [
                'integer',
                'min:0' 
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'The product name cannot exceed 255 characters',
            'price.numeric' => 'The product price must be a number',
            'price.min' => 'The product price must be at least 0.1',
            'stock.integer' => 'The stock quantity must be an integer',
            'stock.min' => 'The stock quantity cannot be negative'
        ];
    }
}
