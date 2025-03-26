<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertMisiJurusanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'misi_jurusans' => 'required|array',
            'misi_jurusans.*.id' => 'nullable|exists:misi_jurusans,id',
            'misi_jurusans.*.misi_jurusan' => 'required|string',
            'misi_jurusans.*.vmt_jurusan_id' => 'required|exists:vmt_jurusans,id',
        ];
    }

    public function messages(): array
    {
        return [
            'misi_jurusans.required' => 'Daftar misi jurusan wajib diisi.',
            'misi_jurusans.array' => 'Format data misi jurusan tidak valid.',
            'misi_jurusans.*.id.exists' => 'ID misi jurusan tidak ditemukan.',
            'misi_jurusans.*.misi_jurusan.required' => 'Misi jurusan wajib diisi.',
            'misi_jurusans.*.misi_jurusan.string' => 'Misi jurusan harus berupa teks.',
            'misi_jurusans.*.misi_jurusan.max' => 'Misi jurusan tidak boleh lebih dari 255 karakter.',
            'misi_jurusans.*.vmt_jurusan_id.required' => 'ID VMT jurusan wajib diisi.',
            'misi_jurusans.*.vmt_jurusan_id.exists' => 'ID VMT jurusan tidak ditemukan.',
        ];
    }
}
