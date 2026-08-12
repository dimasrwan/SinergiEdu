<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $teacher = $this->route('teacher'); // Teacher model instance
        $userId = $teacher ? $teacher->user_id : null;
        $teacherId = $teacher ? $teacher->id : null;

        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $userId,
            'nip' => 'required|string|max:50|unique:teachers,nip,' . $teacherId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => $teacher ? 'nullable|string|min:8' : 'required|string|min:8|confirmed',
            'assignments' => 'nullable|array',
            'assignments.*.class_id' => 'required|exists:classes,id',
            'assignments.*.subject_id' => 'required|exists:subjects,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Guru wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'assignments.*.class_id.required' => 'Kelas pada penugasan wajib dipilih.',
            'assignments.*.class_id.exists' => 'Kelas tidak valid.',
            'assignments.*.subject_id.required' => 'Mata pelajaran pada penugasan wajib dipilih.',
            'assignments.*.subject_id.exists' => 'Mata pelajaran tidak valid.',
        ];
    }
}
