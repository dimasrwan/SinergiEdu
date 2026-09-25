<?php

declare(strict_types=1);

namespace App\Http\Requests\WakaKurikulum;

use App\Services\TenantService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('subject') ? $this->route('subject')->id : null;
        $schoolId = app(TenantService::class)->getSchoolId() ?? auth()->user()->school_id;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('subjects', 'name')
                    ->ignore($id)
                    ->where(fn ($query) => $query->where('school_id', $schoolId)),
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('subjects', 'code')
                    ->ignore($id)
                    ->where(fn ($query) => $query->where('school_id', $schoolId)),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama mata pelajaran wajib diisi.',
            'name.unique' => 'Nama mata pelajaran ini sudah terdaftar di sekolah Anda.',
            'code.required' => 'Kode mata pelajaran wajib diisi.',
            'code.unique' => 'Kode mata pelajaran ini sudah terdaftar di sekolah Anda.',
        ];
    }
}
