<?php

declare(strict_types=1);

namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;

class ReflectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'learning_meeting_id' => 'required|exists:learning_meetings,id',
            'content' => 'required|string|max:3000',
        ];
    }

    public function messages(): array
    {
        return [
            'learning_meeting_id.required' => 'Pertemuan pembelajaran wajib dipilih.',
            'learning_meeting_id.exists' => 'Pertemuan pembelajaran tidak ditemukan.',
            'content.required' => 'Refleksi pembelajaran wajib diisi.',
            'content.max' => 'Isi refleksi tidak boleh melebihi 3000 karakter.',
        ];
    }
}
