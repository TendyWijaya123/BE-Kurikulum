<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'prodi_id' => 'required|exists:prodis,id', // ubah jadi required sesuai controller
            // hapus validasi password karena akan di-generate otomatis
        ];
    }
}