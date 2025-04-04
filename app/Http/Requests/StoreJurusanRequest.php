<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJurusanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ubah ke true agar request bisa digunakan
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/|unique:jurusans,nama',
            'kategori' => 'required|in:Rekayasa,Non Rekayasa',
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama jurusan wajib diisi.',
            'nama.string' => 'Nama jurusan harus berupa teks.',
            'nama.max' => 'Nama jurusan tidak boleh lebih dari 255 karakter.',
            'nama.regex' => 'Nama jurusan tidak boleh mengandung angka atau karakter spesial.',
            'nama.unique' => 'Nama jurusan sudah digunakan. Silakan pilih nama lain.',

            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori harus berupa "Rekayasa" atau "Non Rekayasa".',
        ];
    }
}
