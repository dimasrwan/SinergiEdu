<?php

declare(strict_types=1);

namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;

class SubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|max:20480', // Maks 20MB untuk berkas jawaban
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Anda wajib mengunggah berkas jawaban.',
            'file.max' => 'Ukuran berkas jawaban maksimal 20MB.',
        ];
    }
}
