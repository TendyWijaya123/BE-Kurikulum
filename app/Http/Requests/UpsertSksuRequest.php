<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertSksuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Harus true agar request bisa diproses
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            '*._id' => 'nullable|integer',
            '*.prodiId' => 'required|exists:prodis,id',
            '*.profilLulusan' => 'required|string|max:255',
            '*.kualifikasi' => 'required|string|max:255',
            '*.kategori' => 'required|string|in:Siap Kerja,Siap Usaha',
            '*.kompetensiKerja' => 'required|string',
            '*._id' => 'nullable|exists:sksus,id',
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            '*.prodiId.required' => 'Prodi ID wajib diisi.',
            '*.prodiId.exists' => 'Prodi ID tidak ditemukan dalam database.',
            '*.profilLulusan.required' => 'Profil lulusan wajib diisi.',
            '*.profilLulusan.string' => 'Profil lulusan harus berupa teks.',
            '*.profilLulusan.max' => 'Profil lulusan maksimal 255 karakter.',
            '*.kualifikasi.required' => 'Kualifikasi wajib diisi.',
            '*.kualifikasi.max' => 'Kualifikasi maksimal 255 karakter.',
            '*.kualifikasi.string' => 'Kualifikasi harus berupa teks.',
            '*.kategori.required' => 'Kategori wajib diisi.',
            '*.kategori.string' => 'Kategori harus berupa teks.',
            '*.kategori.in' => 'Kategori harus salah satu dari: A, B, C, D.',
            '*.kompetensiKerja.required' => 'Kompetensi kerja wajib diisi.',
            '*.kompetensiKerja.string' => 'Kompetensi kerja harus berupa teks.',
            '*._id.exists' => 'ID SKSU tidak valid.',
        ];
    }
}
