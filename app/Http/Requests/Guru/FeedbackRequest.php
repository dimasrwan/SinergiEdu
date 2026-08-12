<?php

declare(strict_types=1);

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'title' => 'required|string|max:150',
            'message' => 'required|string',
            'type' => 'required|in:positive,neutral,negative',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Siswa wajib dipilih.',
            'title.required' => 'Judul feedback wajib diisi.',
            'message.required' => 'Isi feedback wajib diisi.',
            'type.required' => 'Tipe feedback wajib dipilih.',
            'type.in' => 'Tipe feedback tidak valid.',
        ];
    }
}
