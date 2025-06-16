<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateVmtPolbanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Izinkan request digunakan
    }

    public function rules(): array
    {
        return [
            'visi_polban' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'visi_polban.required' => 'Visi Polban wajib diisi.',
            'visi_polban.string' => 'Visi Polban harus berupa teks.',
            'visi_polban.max' => 'Visi Polban tidak boleh lebih dari 255 karakter.',
        ];
    }
}
