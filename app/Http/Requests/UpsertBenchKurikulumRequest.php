<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertBenchKurikulumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*._id' => 'nullable|integer',
            '*.programStudi' => 'required|string',
            '*.kategori' => 'required|string|in:Dalam Negeri,Luar Negeri',
            '*.cpl' => 'required|string',
            '*.ppm' => 'nullable|string',
            '*.prodiId' => 'required|integer|exists:prodis,id',
        ];
    }

    public function messages(): array
    {
        return [
            '*.programStudi.required' => 'Program Studi wajib diisi.',
            '*.kategori.required' => 'Kategori wajib diisi.',
            '*.kategori.in' => 'Kategori hanya boleh Luar Negeri dan Dalam Negeri',
            '*.cpl.required' => 'CPL wajib diisi.',
            '*.prodiId.required' => 'Prodi ID wajib diisi.',
            '*.prodiId.exists' => 'Prodi ID tidak valid.',
        ];
    }
}
