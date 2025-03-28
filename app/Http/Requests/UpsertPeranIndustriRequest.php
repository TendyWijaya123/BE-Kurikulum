<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertPeranIndustriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'peran_industri' => 'required|array',
            'peran_industri.*.id' => 'nullable|exists:peran_industris,id',
            'peran_industri.*.jabatan' => 'required|string|max:255',
            'peran_industri.*.deskripsi' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'peran_industri.required' => 'Peran industri wajib diisi.',
            'peran_industri.array' => 'Peran industri harus dalam format array.',
            'peran_industri.*.id.exists' => 'ID peran industri tidak valid.',
            'peran_industri.*.jabatan.required' => 'Jabatan wajib diisi.',
            'peran_industri.*.jabatan.string' => 'Jabatan harus berupa teks.',
            'peran_industri.*.jabatan.max' => 'Jabatan tidak boleh lebih dari 255 karakter.',
            'peran_industri.*.deskripsi.required' => 'Deskripsi wajib diisi.',
            'peran_industri.*.deskripsi.string' => 'Deskripsi harus berupa teks.',
        ];
    }
}
