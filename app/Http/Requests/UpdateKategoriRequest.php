<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKategoriRequest extends FormRequest
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

            'simbol' => 'nullable|string|max:50',

            'kategori_ar' => 'required|string',
            'kategori_ar_musyakal' => 'required|string',
            'kategori_in' => 'required|string',
            'hukum' => 'nullable|string',

            'rofa' => 'nullable|string',
            'nashob' => 'nullable|string',
            'jar' => 'nullable|string',
            'jazm' => 'nullable|string',
        ];
    }
}
