<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengawas_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengawas_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained()->nullOnDelete();
            $table->text('content');
            $table->enum('type', ['positive', 'negative', 'neutral'])->default('neutral');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengawas_feedbacks');
    }
};
