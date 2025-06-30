<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreCategoryRequest",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", maxLength=100, example="Electrónicos"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Productos electrónicos"),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="binary",
 *         description="Imagen de categoría (formatos: jpeg,png,jpg,gif,svg, tamaño máximo: 2MB)"
 *     ),
 *     @OA\Property(property="is_active", type="boolean", example=true)
 * )
 */
class StoreCategoryRequest extends FormRequest
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
            'name' => 'required|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean'
        ];
    }
}
