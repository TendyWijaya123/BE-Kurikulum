<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertTujuanPolbanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tujuan_polbans' => 'required|array',
            'tujuan_polbans.*.id' => 'nullable|exists:tujuan_polbans,id',
            'tujuan_polbans.*.tujuan_polban' => 'required|string',
            'tujuan_polbans.*.vmt_polban_id' => 'required|exists:vmt_polbans,id',
        ];
    }

    public function messages(): array
    {
        return [
            'tujuan_polbans.required' => 'Daftar tujuan polban wajib diisi.',
            'tujuan_polbans.array' => 'Format data tujuan polban tidak valid.',
            'tujuan_polbans.*.id.exists' => 'ID tujuan polban tidak ditemukan.',
            'tujuan_polbans.*.tujuan_polban.required' => 'Tujuan polban wajib diisi.',
            'tujuan_polbans.*.tujuan_polban.string' => 'Tujuan polban harus berupa teks.',
            'tujuan_polbans.*.tujuan_polban.max' => 'Tujuan polban tidak boleh lebih dari 255 karakter.',
            'tujuan_polbans.*.vmt_polban_id.required' => 'ID VMT polban wajib diisi.',
            'tujuan_polbans.*.vmt_polban_id.exists' => 'ID VMT polban tidak ditemukan.',
        ];
    }
}
