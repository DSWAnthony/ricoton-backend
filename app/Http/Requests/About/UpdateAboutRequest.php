<?php

namespace App\Http\Requests\About;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAboutRequest extends FormRequest
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
            'location' => 'nullable|string|max:500',
            'schedule' => 'nullable|string',
            'phone' => 'nullable|string|max:700',
            'instagram' => 'nullable|string|max:800',
            'facebook' => 'nullable|string|max:800',
            'tiktok' => 'nullable|string|max:800',
            'video_ref' => 'nullable|string|max:900',
        ];
    }
}
