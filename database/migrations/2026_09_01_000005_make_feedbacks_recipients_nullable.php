<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom teacher_id & student_id awalnya NOT NULL (migration 2026_08_07_000007).
        // Feedback strategis ke waka/pengawas tidak mengisi teacher/student,
        // sehingga kolom harus diperbolehkan NULL.
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['student_id']);
        });

        Schema::table('feedbacks', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->change();
            $table->foreignId('student_id')->nullable()->change();

            $table->foreign('teacher_id')->references('id')->on('teachers')->cascadeOnDelete();
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['student_id']);
        });

        Schema::table('feedbacks', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable(false)->change();
            $table->foreignId('student_id')->nullable(false)->change();

            $table->foreign('teacher_id')->references('id')->on('teachers')->cascadeOnDelete();
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
        });
    }
};
