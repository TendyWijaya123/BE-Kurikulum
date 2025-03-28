<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVmtJurusanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'visi_jurusan' => 'required|string|max:255',
            'visi_keilmuan_prodi' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'visi_jurusan.required' => 'Visi jurusan wajib diisi.',
            'visi_jurusan.string' => 'Visi jurusan harus berupa teks.',
            'visi_jurusan.max' => 'Visi jurusan tidak boleh lebih dari 255 karakter.',
            'visi_keilmuan_prodi.required' => 'Visi keilmuan prodi wajib diisi.',
            'visi_keilmuan_prodi.string' => 'Visi keilmuan prodi harus berupa teks.',
            'visi_keilmuan_prodi.max' => 'Visi keilmuan prodi tidak boleh lebih dari 255 karakter.',
        ];
    }
}
