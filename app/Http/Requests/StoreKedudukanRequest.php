<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKedudukanRequest extends FormRequest
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
            'id' => 'required|unique:kedudukan,id',
            'id_kalimat' => 'required|exists:kalimat,id',
            'simbol' => 'string|max:50',
            'kedudukan_ar' => 'required|string',
            'kedudukan_ar_musyakal' => 'required|string',
            'kedudukan_in' => 'required|string',
            'irob' => 'string',
        ];
    }
}
