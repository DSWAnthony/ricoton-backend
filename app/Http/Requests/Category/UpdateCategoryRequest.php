<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateCategoryRequest",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", maxLength=100, example="Electrónicos Actualizados"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Productos electrónicos de última generación"),
 *     @OA\Property(property="is_active", type="boolean", example=false)
 * )
 */
class UpdateCategoryRequest extends FormRequest
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
            'is_active' => 'sometimes|boolean'
        ];
    }
}
