<?php

namespace App\Http\Requests\Policy;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdatePolicyRequest",
 *     @OA\Property(property="title", type="string", maxLength=255, example="Política de Privacidad Actualizada"),
 *     @OA\Property(property="description", type="string", example="Detalles actualizados de nuestra política..."),
 *     @OA\Property(property="other_description", type="string", nullable=true, example="Información adicional actualizada")
 * )
 */
class UpdatePolicyRequest extends FormRequest
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
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'other_description' => 'sometimes|string',
        ];
    }
}
