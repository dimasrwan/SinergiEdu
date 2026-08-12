<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PengawasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Middleware handles admin authorization
    }

    public function rules(): array
    {
        $pengawas = $this->route('pengawa'); // Wait, default resource parameter for 'pengawas' is 'pengawa' because Laravel singularizes 'pengawas' to 'pengawa' or 'pengawas'. Let me check. Actually it usually singularizes 'pengawas' to 'pengawa'. Let's explicitly check the parameter name or use `$this->route('pengawas') ?? $this->route('pengawa')`. Let's just use `$this->route('pengawa') ?? $this->route('pengawas')`.
        $pengawasModel = $this->route('pengawa') ?? $this->route('pengawas');
        $userId = $pengawasModel ? $pengawasModel->user_id : null;
        $pengawasId = $pengawasModel ? $pengawasModel->id : null;

        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $userId,
            'nip' => 'nullable|string|max:50|unique:pengawas,nip,' . $pengawasId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => $pengawasModel ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Pengawas wajib diisi.',
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
