<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserAnswerRequest extends FormRequest
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
            'question_id' => 'required|exists:questions,id',
            'level' => 'required|integer|in:1,2,3',
            'answer' => 'required|string',
            'pass' => 'boolean',
            'score' => 'nullable|numeric|min:0|max:100',
            'attempt_count' => 'nullable|integer|min:1',
            'time_spent' => 'nullable|integer|min:0',
            'metadata' => 'nullable|json',
        ];
    }

    /**
     * Prepare data before validation
     */
    protected function prepareForValidation(): void
    {
        // Set user_id from authenticated user
        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }
}
