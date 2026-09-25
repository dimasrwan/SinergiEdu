<?php

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
        Schema::create('collaborative_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('student_assessments');
            $table->string('role_type'); // 'guru', 'ortu', 'waka', 'pengawas'
            $table->foreignId('user_id')->constrained('users');
            $table->text('feedback_content')->nullable();
            $table->text('action_plan')->nullable();
            $table->string('week_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaborative_actions');
    }
};
