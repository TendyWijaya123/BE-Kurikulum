<?php

namespace App\Http\Requests;

use App\Enums\KategoriMataKuliahEnum;
use App\Enums\KategoriMataKuliahPolbanEnum;
use App\Enums\KategoriMataKuliahProdiEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMataKuliahRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode' => 'required|string|size:9|unique:mata_kuliahs,kode,' . $this->route('id'),
            'nama' => 'required|string',
            'kategori' => [
                'nullable',
                'string',
                Rule::in(KategoriMataKuliahEnum::values()),
            ],
            'kategori_mata_kuliah_prodi' => [
                'nullable',
                'string',
                Rule::in(KategoriMataKuliahProdiEnum::values()),
            ],
            'kategori_mata_kuliah_polban' => [
                'nullable',
                'string',
                Rule::in(KategoriMataKuliahPolbanEnum::values()),
            ],
            'tujuan' => 'required|string',
            'semester' => 'nullable|integer|min:1',
            'teori_bt' => 'nullable|integer|min:0',
            'teori_pt' => 'nullable|integer|min:0',
            'teori_m' => 'nullable|integer|min:0',
            'praktek_bt' => 'nullable|integer|min:0',
            'praktek_pt' => 'nullable|integer|min:0',
            'praktek_m' => 'nullable|integer|min:0',
            'kemampuan_akhirs' => 'array',
            'kemampuan_akhirs.*.deskripsi' => 'required|string',
            'kemampuan_akhirs.*.estimasi_beban_belajar' => 'required|numeric',
            'kemampuan_akhirs.*.metode_pembelajaran_ids' => 'array',
            'kemampuan_akhirs.*.bentuk_pembelajaran_ids' => 'array',
            'formulasi_cpa_ids' => 'array',
            'tujuan_belajar' => 'array',
            'tujuan_belajar.*.deskripsi' => 'required|string',
        ];
    }

    /**
     * Get the validation messages for the defined rules.
     */
    public function messages(): array
    {
        return [
            'kode.size' => 'Kode mata kuliah harus terdiri dari 9 karakter.',
            'kode.required' => 'Kode mata kuliah harus diisi.',
            'kode.string' => 'Kode mata kuliah harus berupa teks.',
            'kode.unique' => 'Kode mata kuliah sudah digunakan.',
            'nama.required' => 'Nama mata kuliah harus diisi.',
            'nama.string' => 'Nama mata kuliah harus berupa teks.',
            'kategori.required' => 'Kategori harus diisi.',
            'kategori.string' => 'Kategori harus berupa teks.',
            'kategori.in' => 'Kategori harus salah satu dari: Institusi, Prodi, atau Nasional.',
            'tujuan.required' => 'Tujuan harus diisi.',
            'tujuan.string' => 'Tujuan harus berupa teks.',
            'semester.integer' => 'Semester harus berupa angka.',
            'semester.min' => 'Semester minimal adalah 1.',
            'teori_bt.integer' => 'Teori BT harus berupa angka.',
            'teori_bt.min' => 'Teori BT tidak boleh kurang dari 0.',
            'teori_pt.integer' => 'Teori PT harus berupa angka.',
            'teori_pt.min' => 'Teori PT tidak boleh kurang dari 0.',
            'teori_m.integer' => 'Teori M harus berupa angka.',
            'teori_m.min' => 'Teori M tidak boleh kurang dari 0.',
            'praktek_bt.integer' => 'Praktik BT harus berupa angka.',
            'praktek_bt.min' => 'Praktik BT tidak boleh kurang dari 0.',
            'praktek_pt.integer' => 'Praktik PT harus berupa angka.',
            'praktek_pt.min' => 'Praktik PT tidak boleh kurang dari 0.',
            'praktek_m.integer' => 'Praktik M harus berupa angka.',
            'praktek_m.min' => 'Praktik M tidak boleh kurang dari 0.',
            'kemampuan_akhirs.array' => 'Kemampuan Akhir harus berupa array.',
            'kemampuan_akhirs.*.deskripsi.required' => 'Deskripsi kemampuan akhir harus diisi.',
            'kemampuan_akhirs.*.deskripsi.string' => 'Deskripsi kemampuan akhir harus berupa teks.',
            'kemampuan_akhirs.*.estimasi_beban_belajar.required' => 'Estimasi beban belajar harus diisi.',
            'kemampuan_akhirs.*.estimasi_beban_belajar.numeric' => 'Estimasi beban belajar harus berupa angka.',
            'kemampuan_akhirs.*.metode_pembelajaran_ids.array' => 'Metode pembelajaran harus berupa array.',
            'kemampuan_akhirs.*.bentuk_pembelajaran_ids.array' => 'Bentuk pembelajaran harus berupa array.',
            'formulasi_cpa_ids.array' => 'Formulasi CPA harus berupa array.',
            'tujuan_belajar.array' => 'Tujuan belajar harus berupa array.',
            'tujuan_belajar.*.deskripsi.required' => 'Deskripsi tujuan belajar harus diisi.',
            'tujuan_belajar.*.deskripsi.string' => 'Deskripsi tujuan belajar harus berupa teks.',
        ];
    }
}
