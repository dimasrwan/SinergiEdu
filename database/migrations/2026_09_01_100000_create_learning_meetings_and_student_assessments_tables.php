<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('meeting_number');
            $table->date('meeting_date');
            $table->string('topic');
            $table->text('tools_materials')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique([
                'teacher_id', 'class_id', 'subject_id', 'academic_year_id', 'semester_id', 'meeting_number',
            ], 'learning_meetings_unique_number');
        });

        Schema::create('student_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_meeting_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->integer('pre_test_score')->nullable();
            $table->integer('assignment_score')->nullable();
            $table->integer('post_test_score')->nullable();
            $table->integer('character_score')->nullable();
            $table->integer('memorization_score')->nullable();
            $table->string('memorization_juz', 30)->nullable();
            $table->string('memorization_ayat', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['learning_meeting_id', 'student_id'], 'student_assessments_unique_student_meeting');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('learning_meeting_id')
                ->nullable()
                ->after('subject_id')
                ->constrained()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['learning_meeting_id']);
            $table->dropColumn('learning_meeting_id');
        });

        Schema::dropIfExists('student_assessments');
        Schema::dropIfExists('learning_meetings');
    }
};
