<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class KepalaSekolahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $kepsek = $this->route('kepala_sekolah');
        $userId = $kepsek ? $kepsek->user_id : null;
        $kepsekId = $kepsek ? $kepsek->id : null;

        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $userId,
            'nip' => 'nullable|string|max:50|unique:kepala_sekolahs,nip,' . $kepsekId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => $kepsek ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Kepala Sekolah/Madrasah wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
