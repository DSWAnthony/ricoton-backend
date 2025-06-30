<?php

namespace App\Http\Requests\About;

use Illuminate\Foundation\Http\FormRequest;

class CreateAboutRequest extends FormRequest
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
            'location' => 'required|string|max:255',
            'schedule' => 'required|string',
            'phone' => 'required|string|max:600',
            'instagram' => 'required|string|max:600',
            'facebook' => 'required|string|max:600',
            'tiktok' => 'required|string|max:600',
        ];
    }
}
