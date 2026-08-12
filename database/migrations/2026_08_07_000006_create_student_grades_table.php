<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained()->cascadeOnDelete();
            
            // Komponen Penilaian (0-100)
            $table->integer('pre_test_score')->nullable();
            $table->integer('assignment_score')->nullable();
            $table->integer('post_test_score')->nullable();
            $table->integer('character_score')->nullable();
            $table->integer('memorization_score')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();

            // Seorang siswa hanya punya 1 rekam nilai untuk pelajaran tertentu pada semester tertentu
            $table->unique(['student_id', 'subject_id', 'academic_year_id', 'semester_id'], 'student_grades_unique_combination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_grades');
    }
};
