<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExerciseRequest extends FormRequest
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
            'level' => 'required|integer|in:1,2,3,99',
            'options' => 'nullable|json',
            'explanation' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'metadata' => 'nullable|json',
        ];
    }
}
