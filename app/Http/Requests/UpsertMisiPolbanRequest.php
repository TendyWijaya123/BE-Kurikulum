<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertMisiPolbanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'misi_polbans' => 'required|array',
            'misi_polbans.*.id' => 'nullable',
            'misi_polbans.*.misi_polban' => 'required|string',
            'misi_polbans.*.vmt_polban_id' => 'required|exists:vmt_polbans,id',
        ];
    }

    public function messages(): array
    {
        return [
            'misi_polbans.required' => 'Data misi polban harus diisi.',
            'misi_polbans.array' => 'Format data misi polban tidak valid.',
            'misi_polbans.*.misi_polban.required' => 'Misi Polban harus diisi.',
            'misi_polbans.*.misi_polban.string' => 'Misi Polban harus berupa teks.',
            'misi_polbans.*.misi_polban.max' => 'Misi Polban tidak boleh lebih dari 255 karakter.',
            'misi_polbans.*.vmt_polban_id.required' => 'ID VMT Polban harus diisi.',
            'misi_polbans.*.vmt_polban_id.exists' => 'ID VMT Polban tidak ditemukan.',
        ];
    }
}
