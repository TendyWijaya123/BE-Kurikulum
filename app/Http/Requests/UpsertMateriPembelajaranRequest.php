<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertMateriPembelajaranRequest extends FormRequest
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
            '*.prodiId' => 'required|exists:prodis,id',
            '*.code' => 'required|string|max:255',
            '*.description' => 'required|string',
            '*.cognitifProses' => 'required|string',
            '*.knowledgeDimension' => 'nullable|array',
            '*.knowledgeDimension.*' => 'string|exists:knowledge_dimensions,code',
        ];
    }

    public function messages()
    {
        return [
            '*.prodiId.required' => 'Prodi ID wajib diisi.',
            '*.prodiId.exists' => 'Prodi ID tidak valid.',
            '*.code.required' => 'Kode wajib diisi.',
            '*.code.string' => 'Kode harus berupa string.',
            '*.code.max' => 'Kode maksimal 255 karakter.',
            '*.description.required' => 'Deskripsi wajib diisi.',
            '*.description.string' => 'Deskripsi harus berupa string.',
            '*.cognitifProses.required' => 'Cognitive Process wajib diisi.',
            '*.cognitifProses.string' => 'Cognitive Process harus berupa string.',
            '*.knowledgeDimension.array' => 'Knowledge Dimension harus berupa array.',
            '*.knowledgeDimension.*.string' => 'Setiap Knowledge Dimension harus berupa string.',
            '*.knowledgeDimension.*.exists' => 'Knowledge Dimension tidak valid.',
        ];
    }
}
