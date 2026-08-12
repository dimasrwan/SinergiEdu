<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'school_name' => 'required|string|max:150',
            'school_npsn' => 'nullable|string|max:20',
            'school_address' => 'nullable|string|max:500',
            'school_phone' => 'nullable|string|max:20',
            'school_email' => 'nullable|email|max:100',
            'school_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'school_name.required' => 'Nama Sekolah/Madrasah wajib diisi.',
            'school_email.email' => 'Format email tidak valid.',
            'school_logo.image' => 'File logo harus berupa gambar.',
            'school_logo.mimes' => 'Format logo yang diizinkan hanya: jpeg, png, jpg, webp.',
            'school_logo.max' => 'Ukuran logo tidak boleh lebih dari 2MB.',
        ];
    }
}
