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
        Schema::table('teacher_subjects', function (Blueprint $table) {
            $table->foreignId('academic_year_id')->nullable()->after('class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->nullable()->after('academic_year_id')->constrained()->cascadeOnDelete();
            
            // Note: Since this is altering an existing table, we allow nullable temporarily,
            // but for new inserts we expect them. The application logic will enforce uniqueness.
            $table->unique(['teacher_id', 'subject_id', 'class_id', 'academic_year_id', 'semester_id'], 'teacher_subject_unique_assignment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_subjects', function (Blueprint $table) {
            $table->dropUnique('teacher_subject_unique_assignment');
            $table->dropForeign(['academic_year_id']);
            $table->dropForeign(['semester_id']);
            $table->dropColumn(['academic_year_id', 'semester_id']);
        });
    }
};
