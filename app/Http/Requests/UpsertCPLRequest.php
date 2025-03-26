<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertCPLRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cpls' => 'required|array',
            'cpls.*.id' => 'nullable|integer|exists:cpls,id',
            'cpls.*.keterangan' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'cpls.required' => 'Data CPL harus diisi.',
            'cpls.array' => 'Format data CPL tidak valid.',
            'cpls.*.id.integer' => 'ID CPL harus berupa angka.',
            'cpls.*.id.exists' => 'ID CPL tidak ditemukan.',
            'cpls.*.keterangan.required' => 'Keterangan CPL harus diisi.',
            'cpls.*.keterangan.string' => 'Keterangan CPL harus berupa teks.',
            'cpls.*.keterangan.max' => 'Keterangan CPL tidak boleh lebih dari 255 karakter.',
        ];
    }
}
