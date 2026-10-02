<?php

declare(strict_types=1);

namespace App\Http\Requests\WakaKurikulum;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SemesterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Tolak semester SAMA pada tahun ajaran yang SAMA saja.
        // Ganjil + Genap pada tahun ajaran sama tetap BOLEH.
        $uniqueSemester = Rule::unique('semesters', 'name')
            ->where('academic_year_id', $this->input('academic_year_id'))
            ->where('school_id', $this->user()?->school_id);

        $currentSemester = $this->route('semester');
        if ($currentSemester instanceof \App\Models\Semester) {
            $uniqueSemester->ignore($currentSemester->id);
        }

        return [
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => ['required', 'string', 'max:50', $uniqueSemester],
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
            'academic_year_id.exists' => 'Tahun ajaran tidak valid.',
            'name.required' => 'Nama semester wajib diisi.',
            'name.unique' => 'Semester ini sudah terdaftar pada tahun ajaran yang dipilih.',
        ];
    }
}
