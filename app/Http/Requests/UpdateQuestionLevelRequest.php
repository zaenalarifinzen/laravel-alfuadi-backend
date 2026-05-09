<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionLevelRequest extends FormRequest
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
        $levelId = $this->route('question_level');

        return [
            'name' => 'sometimes|required|string|max:100',
            'level_number' => 'sometimes|required|integer|unique:question_levels,level_number,' . $levelId,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:100',
            'min_score' => 'nullable|integer|min:0',
            'question_count' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'metadata' => 'nullable|json',
        ];
    }
}
