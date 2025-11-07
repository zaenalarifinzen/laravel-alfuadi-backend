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
            'verse_id' => 'required|integer',
            'merged_map' => 'nullable|array',
            'edited_groups' => 'nullable|array',
            'deleted_ids' => 'nullable|array',
            'new_groups' => 'nullable|array',
        ];
    }
}
