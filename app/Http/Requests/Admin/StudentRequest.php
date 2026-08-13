<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $student = $this->route('student'); // Student model instance
        $userId = $student ? $student->user_id : null;
        $studentId = $student ? $student->id : null;

        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $userId,
            'nis' => 'required|string|max:50|unique:students,nis,' . $studentId,
            'gender' => 'required|in:L,P',
            'date_of_birth' => 'required|date',
            'parent_id' => 'nullable|exists:parents,id',
            'password' => $student ? 'nullable|string|min:8' : 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Siswa wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'date_of_birth.required' => 'Tanggal lahir wajib diisi.',
            'class_id.required' => 'Kelas wajib dipilih.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
        ];
    }
}
