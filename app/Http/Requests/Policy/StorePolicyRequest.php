<?php

namespace App\Http\Requests\Policy;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StorePolicyRequest",
 *     required={"title", "description"},
 *     @OA\Property(property="title", type="string", maxLength=255, example="Política de Privacidad"),
 *     @OA\Property(property="description", type="string", example="Detalles de nuestra política de privacidad..."),
 *     @OA\Property(property="other_description", type="string", nullable=true, example="Información adicional sobre políticas")
 * )
 */
class StorePolicyRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'other_description' => 'nullable|string',
        ];
    }
}
