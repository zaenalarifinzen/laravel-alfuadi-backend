<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKalimatRequest extends FormRequest
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
            'id' => 'required:unique:kalimats,id',
            'kalimat_ar' => 'required|string|max:255',
            'kalimat_ar_musyakal' => 'required|string|max:255',
            'kalimat_in' => 'required|string|max:255',
        ];
    }
}
