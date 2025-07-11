<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreSettingRequest",
 *     required={"company_name", "company_description"},
 *     @OA\Property(property="company_name", type="string", maxLength=255, example="Mi Empresa S.A."),
 *     @OA\Property(property="company_description", type="string", maxLength=255, example="Descripción de la empresa"),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="binary",
 *         description="Imagen de logo (formatos: jpeg,png,jpg,gif,svg, tamaño máximo: 2MB)"
 *     )
 * )
 */
class StoreSettingRequest extends FormRequest
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
            'company_name' => 'required|string|max:255',
            'company_description' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
