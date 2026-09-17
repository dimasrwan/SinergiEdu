<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentId = 1;
        $teacherId = 1; 

        \App\Models\Assignment::create([
            'teacher_id' => $teacherId, 'class_id' => 1, 'subject_id' => 1,
            'title' => 'Tugas Matematika', 'description' => 'Kerjakan soal halaman 10',
            'deadline' => now()->addDays(7),
        ]);

        \App\Models\Material::create([
            'teacher_id' => $teacherId, 'class_id' => 1, 'subject_id' => 1,
            'title' => 'Materi Aljabar', 'description' => 'Dasar-dasar aljabar',
        ]);

        \App\Models\StudentGrade::create([
            'student_id' => $studentId, 'teacher_id' => $teacherId, 'class_id' => 1, 'subject_id' => 1,
            'academic_year_id' => 1, 'semester_id' => 1,
            'pre_test_score' => 80, 'assignment_score' => 85,
            'post_test_score' => 90, 'character_score' => 90, 'memorization_score' => 85,
        ]);

        \App\Models\Feedback::create([
            'teacher_id' => $teacherId, 'student_id' => $studentId, 'subject_id' => 1,
            'title' => 'Kerja bagus', 'message' => 'Pertahankan prestasimu', 'type' => 'positive',
        ]);

        // StudentReflection mungkin butuh learning_meeting_id yang valid
        // Coba isi seadanya, jika error lewati
        try {
            \App\Models\StudentReflection::create([
                'student_id' => $studentId, 'learning_meeting_id' => 1,
                'content' => 'Saya merasa senang belajar materi hari ini.',
            ]);
        } catch (\Exception $e) {
            // skip
        }
    }
}
