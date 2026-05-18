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
            'pass' => 'nullable|boolean',
            'score' => 'nullable|numeric|min:0|max:100',
            'attempt_count' => 'nullable|integer|min:1',
            'time_spent' => 'nullable|integer|min:0',
            'metadata' => 'nullable|json',
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages()
    {
        return [
            'question_id.required' => 'question_id wajib diisi',
            'question_id.exists' => 'question_id tidak ditemukan di tabel questions',
            'level.required' => 'level wajib diisi',
            'level.in' => 'level harus 1, 2, atau 3',
            'score.numeric' => 'score harus berupa angka',
            'metadata.json' => 'metadata harus berupa JSON',
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
