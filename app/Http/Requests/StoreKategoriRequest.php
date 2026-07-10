<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKategoriRequest extends FormRequest
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
            'id' => 'required|unique:kategori,id',
            'id_kalimat' => 'required|exists:kalimat,id',

            'simbol' => 'nullable|string|max:50',

            'kategori_ar' => 'required|string|max:255',
            'kategori_ar_musyakal' => 'required|string|max:255',
            'kategori_in' => 'required|string|max:255',
            'hukum' => 'nullable|string|max:255',

            'rofa' => 'nullable|string|max:255',
            'nashob' => 'nullable|string|max:255',
            'jar' => 'nullable|string|max:255',
            'jazm' => 'nullable|string|max:255',
        ];
    }
}
