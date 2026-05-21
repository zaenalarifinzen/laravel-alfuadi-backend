<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuestionRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'content' => [
                'sometimes',
                Rule::requiredIf(fn () => $this->input('type') !== 'analysis'),
                'string',
                Rule::prohibitedIf(fn () => $this->input('type') === 'analysis'),
            ],
            'level' => 'sometimes|required|integer|in:1,2,3',
            'type' => 'sometimes|required|in:multiple_choice,short_answer,essay,analysis',
            'options' => 'nullable|json',
            'correct_answer' => [
                'sometimes',
                Rule::requiredIf(fn () => $this->input('type') !== 'analysis'),
                'string',
                Rule::prohibitedIf(fn () => $this->input('type') === 'analysis'),
            ],
            'explanation' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'metadata' => 'nullable|json',
        ];
    }
}
