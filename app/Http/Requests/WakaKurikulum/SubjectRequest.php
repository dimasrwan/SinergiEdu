<?php

declare(strict_types=1);

namespace App\Http\Requests\WakaKurikulum;

use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('subject') ? $this->route('subject')->id : null;

        return [
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:subjects,code,' . $id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama mata pelajaran wajib diisi.',
            'code.required' => 'Kode mata pelajaran wajib diisi.',
            'code.unique' => 'Kode mata pelajaran sudah terdaftar.',
        ];
    }
}
