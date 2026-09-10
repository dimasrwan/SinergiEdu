<?php

declare(strict_types=1);

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'learning_meeting_id' => 'nullable|exists:learning_meetings,id',
            'material_id' => 'nullable|exists:materials,id',
            'deadline' => 'required|date|after_or_equal:today',
            'attachment' => 'nullable|file|max:20480', // Maks 20MB untuk lampiran tugas
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul tugas wajib diisi.',
            'description.required' => 'Deskripsi tugas wajib diisi.',
            'class_id.required' => 'Kelas sasaran wajib dipilih.',
            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'deadline.required' => 'Tenggat waktu (deadline) wajib ditentukan.',
            'deadline.after_or_equal' => 'Tenggat waktu tidak boleh kurang dari hari ini.',
            'attachment.max' => 'Ukuran berkas lampiran maksimal 20MB.',
        ];
    }
}
