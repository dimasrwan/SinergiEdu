<?php

declare(strict_types=1);

namespace App\Http\Requests\WakaKurikulum;

use Illuminate\Foundation\Http\FormRequest;

class AcademicYearRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan ini.
     */
    public function rules(): array
    {
        $academicYear = $this->route('academic_year');
        $id = $academicYear instanceof \App\Models\AcademicYear ? $academicYear->id : $academicYear;
        $schoolId = ($academicYear instanceof \App\Models\AcademicYear ? $academicYear->school_id : null)
            ?? app(\App\Services\TenantService::class)->getSchoolId()
            ?? auth()->user()?->school_id;

        return [
            'year' => [
                'required',
                'string',
                \Illuminate\Validation\Rule::unique('academic_years', 'year')
                    ->ignore($id)
                    ->where(fn ($query) => $query->where('school_id', $schoolId)),
            ],
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * Dapatkan pesan kesalahan kustom untuk aturan yang didefinisikan.
     */
    public function messages(): array
    {
        return [
            'year.required' => 'Tahun ajaran wajib diisi.',
            'year.unique' => 'Tahun ajaran sudah terdaftar.',
        ];
    }
}
