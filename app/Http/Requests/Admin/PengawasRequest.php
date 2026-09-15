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
        $param = $this->route('pengawas') ?? $this->route('pengawa');
        $pengawasModel = $param instanceof \App\Models\Pengawas ? $param : ($param ? \App\Models\Pengawas::find($param) : null);
        $userId = $pengawasModel ? $pengawasModel->user_id : null;
        $pengawasId = $pengawasModel ? $pengawasModel->id : null;

        $user = auth()->user();
        $isSchoolAdmin = $user && $user->role && $user->role->name === 'admin';

        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $userId,
            'nip' => 'nullable|string|max:50|unique:pengawas,nip,' . $pengawasId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => $pengawasModel ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'schools' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) use ($isSchoolAdmin, $user) {
                    if ($isSchoolAdmin && is_array($value)) {
                        foreach ($value as $schoolId) {
                            if ((int) $schoolId !== (int) $user->school_id) {
                                $fail('Admin Sekolah tidak diperbolehkan memberikan akses sekolah lain.');
                            }
                        }
                    }
                }
            ],
            'schools.*' => 'exists:schools,id',
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
            'schools.required' => 'Pengawas harus memiliki minimal satu sekolah penugasan.',
            'schools.min' => 'Pengawas harus memiliki minimal satu sekolah penugasan.',
        ];
    }
}
