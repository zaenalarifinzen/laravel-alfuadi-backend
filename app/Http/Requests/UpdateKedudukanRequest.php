<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKedudukanRequest extends FormRequest
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
            'id_kalimat' => 'required|exists:kalimat,id',
            'order' => 'required|integer',
            'simbol' => 'nullable|string|max:50',
            'kedudukan_ar' => 'required|string|max:255',
            'kedudukan_ar_musyakal' => 'required|string|max:255',
            'kedudukan_in' => 'required|string|max:255',
            'irob' => 'nullable|string|max:255',
        ];
    }
}
