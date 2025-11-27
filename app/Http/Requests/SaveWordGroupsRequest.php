<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveWordGroupsRequest extends FormRequest
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
            'verse_id' => ['required', 'integer', 'exists:verses,id'],
            'surah_id' => ['nullable', 'integer', 'exists:surahs,id'],
            'verse_number' => ['nullable', 'integer'],

            'merged_map' => ['nullable', 'array'],
            'merged_map.*' => ['integer'],

            'edited_groups' => ['nullable', 'array'],
            'edited_groups.*.id' => ['required_with:edited_groups', 'integer', 'exists:word_groups,id'],
            'edited_groups.*.text' => ['nullable', 'string'],
            'edited_groups.*.order_number' => ['nullable', 'integer'],

            'deleted_ids' => ['nullable', 'array'],
            'deleted_ids.*' => ['integer', 'exists:word_groups,id'],
        ];
    }

    public function messages() : array
    {
        return [
            'verse_id.required' => 'verse_id wajib dikirim.',
            'verse_id.exists' => 'verse_id tidak ditemukan di data.',
            'merged_map.array' => 'merged_map harus berupa array oldId:newId.',
            'deleted_ids.array' => 'deleted_ids harus berupa array id numerik.',
        ];
    }
}