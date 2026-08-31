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
        Schema::create('parent_supports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('week_number')->default('Minggu 1');
            $table->text('support_description')->comment('Dukungan orang tua di rumah: les, belajar kelompok, PR, dll');
            $table->text('general_feedback')->nullable()->comment('Umpan balik orang tua terhadap capaian hasil belajar');
            $table->text('action_plan')->nullable()->comment('Rencana aksi orang tua untuk meningkatkan hasil belajar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_supports');
    }
};
