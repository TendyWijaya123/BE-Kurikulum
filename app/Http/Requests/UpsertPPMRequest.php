<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertPPMRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ppms' => 'required|array',
            'ppms.*.id' => 'nullable|exists:ppms,id',
            'ppms.*.deskripsi' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'ppms.required' => 'Data PPM harus diisi.',
            'ppms.array' => 'Format data PPM tidak valid.',
            'ppms.*.id.exists' => 'ID PPM tidak ditemukan.',
            'ppms.*.deskripsi.required' => 'Deskripsi PPM harus diisi.',
            'ppms.*.deskripsi.string' => 'Deskripsi PPM harus berupa teks.',
            'ppms.*.deskripsi.max' => 'Deskripsi PPM tidak boleh lebih dari 255 karakter.',
        ];
    }
}
