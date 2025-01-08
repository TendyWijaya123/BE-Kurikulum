<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateKurikulumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Sesuaikan jika ada otorisasi khusus.
    }

    public function rules(): array
    {
        return [
            'tahun_awal' => 'required|integer|min:1900|max:2100',
            'tahun_akhir' => 'required|integer|min:1900|max:2100|gte:tahun_awal',
            'prodi_id' => 'required|exists:prodis,id',
        ];
    }
}
