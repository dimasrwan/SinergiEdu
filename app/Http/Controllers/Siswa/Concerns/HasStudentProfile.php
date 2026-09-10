<?php

declare(strict_types=1);

namespace App\Http\Controllers\Siswa\Concerns;

use App\Models\Student;
use Illuminate\Http\RedirectResponse;

trait HasStudentProfile
{
    private function getStudentProfile(): ?Student
    {
        return Student::where('user_id', auth()->id())->first();
    }

    private function requireStudentProfile(): Student
    {
        $student = $this->getStudentProfile();

        if (!$student) {
            abort(404, 'Profil siswa tidak ditemukan. Silakan hubungi administrator.');
        }

        return $student;
    }
}
