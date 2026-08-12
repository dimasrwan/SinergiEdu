<?php

declare(strict_types=1);

namespace App\Http\Requests\WakaKurikulum;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'grade_level' => 'required|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kelas wajib diisi.',
            'grade_level.required' => 'Tingkat kelas wajib dipilih.',
        ];
    }
}
