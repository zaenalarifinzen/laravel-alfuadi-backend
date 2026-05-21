<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuestionRequest extends FormRequest
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
            'description' => 'nullable|string',
            'content' => [
                Rule::requiredIf(fn () => $this->input('type') !== 'analysis'),
                'string',
                Rule::prohibitedIf(fn () => $this->input('type') === 'analysis'),
            ],
            'level' => 'required|integer|in:1,2,3',
            'type' => 'required|in:multiple_choice,short_answer,essay,analysis',
            'verse_id' => [
                Rule::requiredIf(fn () => $this->input('type') === 'analysis'),
                'nullable',
                'exists:verses,id',
            ],
            'options' => 'nullable|json',
            'correct_answer' => [
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

    /**
     * Prepare data sebelum validation
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'created_by' => auth()->id(),
        ]);
    }
}
