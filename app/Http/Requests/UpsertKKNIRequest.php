<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertKKNIRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dataSource' => 'required|array|min:1',
            'dataSource.*._id' => 'nullable|integer',
            'dataSource.*.code' => 'required|string|max:255',
            'dataSource.*.description' => 'required|string|max:5000',
            'dataSource.*.prodiId' => 'required|integer|exists:prodis,id',
            'selectedPengetahuan' => 'nullable|array',
            'selectedKemampuanKerja' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'dataSource.required' => 'Data tidak boleh kosong.',
            'dataSource.*.code.required' => 'Kode wajib diisi.',
            'dataSource.*.description.required' => 'Deskripsi wajib diisi.',
            'dataSource.*.prodiId.required' => 'prodiId wajib diisi.',
            'dataSource.*.prodiId.exists' => 'prodiId tidak ditemukan dalam database.',
        ];
    }
}
