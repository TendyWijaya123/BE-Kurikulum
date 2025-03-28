<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateKurikulumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tahun_awal' => [
                'required',
                'integer',
                'min:1900',
                'max:2100',
            ],
            'tahun_akhir' => [
                'required',
                'integer',
                'min:1900',
                'max:2100',
                'gte:tahun_awal',
            ],
            'prodi_id' => 'required|exists:prodis,id',

            'tahun_awal' => [
                Rule::unique('kurikulums')->where(function ($query) {
                    return $query->where('tahun_akhir', $this->tahun_akhir)
                        ->where('prodi_id', $this->prodi_id);
                }),
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'tahun_awal.required' => 'Tahun awal wajib diisi.',
            'tahun_awal.integer' => 'Tahun awal harus berupa angka.',
            'tahun_awal.min' => 'Tahun awal tidak boleh kurang dari 1900.',
            'tahun_awal.max' => 'Tahun awal tidak boleh lebih dari 2100.',

            'tahun_akhir.required' => 'Tahun akhir wajib diisi.',
            'tahun_akhir.integer' => 'Tahun akhir harus berupa angka.',
            'tahun_akhir.min' => 'Tahun akhir tidak boleh kurang dari 1900.',
            'tahun_akhir.max' => 'Tahun akhir tidak boleh lebih dari 2100.',
            'tahun_akhir.gte' => 'Tahun akhir harus lebih besar atau sama dengan tahun awal.',

            'prodi_id.required' => 'Program studi wajib dipilih.',
            'prodi_id.exists' => 'Program studi yang dipilih tidak valid.',

            'tahun_awal.unique' => 'Kombinasi tahun awal dan tahun akhir sudah ada dalam sistem.',
        ];
    }
}
