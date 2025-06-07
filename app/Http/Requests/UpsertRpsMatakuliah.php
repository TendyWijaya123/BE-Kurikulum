<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertRpsMatakuliah extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array',
            'items.*.mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'items.*.minggu' => 'required|integer',
            'items.*.pokok_bahasan' => 'nullable|string',
            'items.*.kategori' => 'required|string|in:ETS,EAS,Reguler',
            'items.*.modalitas_pembelajaran' => 'nullable|string',
            'items.*.media_pembelajaran' => 'nullable|string',
            'items.*.metode_pembelajaran' => 'nullable|string',
            'items.*.strategi_pembelajaran' => 'nullable|string',
            'items.*.bentuk_pembelajaran' => 'nullable|string',
            'items.*.hasil_belajar' => 'nullable|string',
            'items.*.kemampuan_akhir' => 'nullable|string',
            'items.*.sumber_belajar' => 'nullable|string',
            'items.*.instrumen_penilaians' => 'nullable|array',
            'items.*.instrumen_penilaians.*.kategori' => 'required|string|in:Project,Quiz,Case Study,Tugas,ETS,EAS',
            'items.*.instrumen_penilaians.*.tujuan_belajar_id' => 'nullable|exists:tujuan_belajar_rps,id',
            'items.*.instrumen_penilaians.*.cpl_id' => 'nullable|exists:cpls,id',
            'items.*.instrumen_penilaians.*.rps_id' => 'nullable|exists:rps_matakuliah,id',
            'items.*.instrumen_penilaians.*.bobot_penilaian' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Data mingguan harus diisi.',
            'items.*.mata_kuliah_id.required' => 'Mata kuliah harus diisi.',
            'items.*.mata_kuliah_id.exists' => 'Mata kuliah tidak ditemukan dalam database.',
            'items.*.minggu.required' => 'Minggu keberapa harus diisi.',
            'items.*.minggu.integer' => 'Nilai minggu harus berupa angka bulat.',
            'items.*.pokok_bahasan.string' => 'Pokok bahasan harus berupa teks.',
            'items.*.kategori.required' => 'Kategori harus diisi.',
            'items.*.kategori.string' => 'Kategori harus berupa teks.',
            'items.*.kategori.in' => 'Kategori harus salah satu dari: ETS, EAS, atau Reguler.',
            'items.*.modalitas_pembelajaran.string' => 'Modalitas pembelajaran harus berupa teks.',
            'items.*.media_pembelajaran.string' => 'Media pembelajaran harus berupa teks.',
            'items.*.metode_pembelajaran.string' => 'Metode pembelajaran harus berupa teks.',
            'items.*.strategi_pembelajaran.string' => 'Strategi pembelajaran harus berupa teks.',
            'items.*.bentuk_pembelajaran.string' => 'Bentuk pembelajaran harus berupa teks.',
            'items.*.hasil_belajar.string' => 'Hasil belajar harus berupa teks.',
            'items.*.kemampuan_akhir.string' => 'Kemampuan akhir harus berupa teks.',
            'items.*.sumber_belajar.string' => 'Sumber belajar harus berupa teks.',
            'items.*.instrumen_penilaians.array' => 'Instrumen penilaian harus berupa array.',
            'items.*.instrumen_penilaians.*.kategori.required' => 'Kategori pada instrumen penilaian harus diisi.',
            'items.*.instrumen_penilaians.*.kategori.string' => 'Kategori pada instrumen penilaian harus berupa teks.',
            'items.*.instrumen_penilaians.*.kategori.in' => 'Kategori instrumen harus salah satu dari: Project, Quiz, Case Study, Tugas, ETS, atau EAS.',
            'items.*.instrumen_penilaians.*.tujuan_belajar_id.exists' => 'Tujuan belajar tidak valid.',
            'items.*.instrumen_penilaians.*.cpl_id.exists' => 'CPL tidak valid.',
            'items.*.instrumen_penilaians.*.rps_id.exists' => 'RPS ID tidak valid.',
            'items.*.instrumen_penilaians.*.bobot_penilaian.required' => 'Bobot penilaian harus diisi.',
            'items.*.instrumen_penilaians.*.bobot_penilaian.numeric' => 'Bobot penilaian harus berupa angka.',
        ];
    }
}
