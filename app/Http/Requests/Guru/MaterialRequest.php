<?php

declare(strict_types=1);

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;

class MaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'nullable|file|mimes:pdf|max:10240', // PDF Max 10MB
            'video' => 'nullable|file|mimes:mp4,mkv,avi|max:51200', // Video Max 50MB
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul materi wajib diisi.',
            'class_id.required' => 'Kelas wajib dipilih.',
            'class_id.exists' => 'Kelas tidak valid.',
            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'subject_id.exists' => 'Mata pelajaran tidak valid.',
            'file.mimes' => 'Berkas materi harus berupa file PDF.',
            'file.max' => 'Berkas PDF maksimal berukuran 10MB.',
            'video.mimes' => 'Berkas video harus berupa MP4, MKV, atau AVI.',
            'video.max' => 'Berkas video maksimal berukuran 50MB.',
        ];
    }
}
