<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertTeknologiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*.id' => 'nullable|integer',
            '*.deskripsi' => 'required|string|max:5000',
            '*.link_sumber' => 'nullable|url',
        ];
    }

    public function messages(): array
    {
        return [
            '*.deskripsi.required' => 'Deskripsi wajib diisi.',
            '*.deskripsi.max' => 'Deskripsi maksimal 5000 karakter.',
            '*.link_sumber.url' => 'Link sumber harus berupa URL yang valid.',
        ];
    }
}
