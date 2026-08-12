<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ParentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $parent = $this->route('parent'); // StudentParent model instance
        $userId = $parent ? $parent->user_id : null;

        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $userId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => $parent ? 'nullable|string|min:8' : 'required|string|min:8',
            'students' => 'nullable|array',
            'students.*' => 'exists:students,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Orang Tua wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
        ];
    }
}
