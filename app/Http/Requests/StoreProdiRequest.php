<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdiRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan ini.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
            'jenjang' => 'required|in:D3,D4,S1,S2,S3',
            'kode' => 'required|string|size:2|unique:prodis',
            'jurusan_id' => 'required|exists:jurusans,id',
        ];
    }

    /**
     * Pesan kesalahan kustom untuk aturan validasi.
     */
    public function messages(): array
    {
        return [

            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa string.',
            'name.max' => 'Nama prodi  maksimal 255 karakter.',
            'name.regex' => 'Nama  prodi tidak boleh angka atau  karakter spesial',

            'jenjang.required' => 'Jenjang wajib diisi.',
            'jenjang.in' => 'Jenjang harus salah satu dari: D3, D4, S1, S2, S3.',
            'kode.required' => 'Kode wajib diisi.',
            'kode.string' => 'Kode harus berupa string.',
            'kode.max' => 'Kode maksimal 50 karakter.',
            'kode.unique' => 'Kode sudah digunakan.',
            'kode.size' => 'Kode tidak boleh lebih dari dua karakter.',
            'jurusan_id.required' => 'Jurusan wajib diisi.',
            'jurusan_id.exists' => 'Jurusan tidak valid.',
        ];
    }
}
