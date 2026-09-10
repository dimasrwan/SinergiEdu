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
        Schema::create('waka_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $table->string('week_number')->default('Minggu 1');
            $table->text('feedback')->nullable()->comment('Umpan balik (feedback) Secara Umum dari Waka Kurikulum');
            $table->text('action_plan')->nullable()->comment('Rencana Aksi Waka Kurikulum untuk meningkatkan hasil belajar');
            $table->timestamps();

            $table->unique(['student_id', 'academic_year_id', 'semester_id', 'week_number'], 'waka_feedbacks_unique_combination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waka_feedbacks');
    }
};
