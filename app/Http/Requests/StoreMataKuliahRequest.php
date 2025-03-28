<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMataKuliahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode' => 'required|string|unique:mata_kuliahs,kode,' . $this->id,
            'nama' => 'required|string',
            'tujuan' => 'nullable|string',
            'kategori' => 'nullable|string|in:Institusi,Prodi,Nasional',
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
            'tujuan_belajar' => 'array',
            'tujuan_belajar.*.deskripsi' => 'required|string',
            'formulasi_cpa_ids' => 'array',
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode mata kuliah wajib diisi.',
            'kode.string' => 'Kode mata kuliah harus berupa teks.',
            'kode.unique' => 'Kode mata kuliah sudah digunakan.',
            'nama.required' => 'Nama mata kuliah wajib diisi.',
            'nama.string' => 'Nama mata kuliah harus berupa teks.',
            'tujuan.string' => 'Tujuan harus berupa teks.',
            'kategori.string' => 'Kategori harus berupa teks.',
            'kategori.in' => 'Kategori harus salah satu dari: Institusi, Prodi, Nasional.',
            'semester.integer' => 'Semester harus berupa angka.',
            'semester.min' => 'Semester minimal bernilai 1.',
            'teori_bt.integer' => 'Teori BT harus berupa angka.',
            'teori_bt.min' => 'Teori BT minimal bernilai 0.',
            'teori_pt.integer' => 'Teori PT harus berupa angka.',
            'teori_pt.min' => 'Teori PT minimal bernilai 0.',
            'teori_m.integer' => 'Teori M harus berupa angka.',
            'teori_m.min' => 'Teori M minimal bernilai 0.',
            'praktek_bt.integer' => 'Praktek BT harus berupa angka.',
            'praktek_bt.min' => 'Praktek BT minimal bernilai 0.',
            'praktek_pt.integer' => 'Praktek PT harus berupa angka.',
            'praktek_pt.min' => 'Praktek PT minimal bernilai 0.',
            'praktek_m.integer' => 'Praktek M harus berupa angka.',
            'praktek_m.min' => 'Praktek M minimal bernilai 0.',
            'kemampuan_akhirs.array' => 'Kemampuan akhir harus berupa array.',
            'kemampuan_akhirs.*.deskripsi.required' => 'Deskripsi kemampuan akhir wajib diisi.',
            'kemampuan_akhirs.*.deskripsi.string' => 'Deskripsi kemampuan akhir harus berupa teks.',
            'kemampuan_akhirs.*.estimasi_beban_belajar.required' => 'Estimasi beban belajar wajib diisi.',
            'kemampuan_akhirs.*.estimasi_beban_belajar.numeric' => 'Estimasi beban belajar harus berupa angka.',
            'kemampuan_akhirs.*.metode_pembelajaran_ids.array' => 'Metode pembelajaran harus berupa array.',
            'kemampuan_akhirs.*.bentuk_pembelajaran_ids.array' => 'Bentuk pembelajaran harus berupa array.',
            'tujuan_belajar.array' => 'Tujuan belajar harus berupa array.',
            'tujuan_belajar.*.deskripsi.required' => 'Deskripsi tujuan belajar wajib diisi.',
            'tujuan_belajar.*.deskripsi.string' => 'Deskripsi tujuan belajar harus berupa teks.',
            'formulasi_cpa_ids.array' => 'Formulasi CPA harus berupa array.',
        ];
    }
}
