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
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:150',
            'description' => 'nullable|string',
            'image' => 'sometimes|mimes:png,jpeg,jpg,webp,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ];
    }
}
