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
        $id = $this->route('academic_year') ? $this->route('academic_year')->id : null;

        return [
            'year' => 'required|string|unique:academic_years,year,' . $id,
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
