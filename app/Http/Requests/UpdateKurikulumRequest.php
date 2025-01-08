<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKurikulumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tahun_awal' => 'sometimes|integer|min:1900|max:2100',
            'tahun_akhir' => 'sometimes|integer|min:1900|max:2100|gte:tahun_awal',
            'is_active' => 'sometimes|boolean',
            'prodi_id' => 'sometimes|exists:prodis,id',
        ];
    }
}
