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
        Schema::table('student_grades', function (Blueprint $table) {
            $table->text('supervisor_feedback')->nullable()->comment('Feedback dari pengawas');
            $table->text('supervisor_action_plan')->nullable()->comment('Rencana aksi dari pengawas');
            $table->enum('supervisor_priority', ['low', 'medium', 'high'])->default('medium')->comment('Prioritas rencana aksi');
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete()->comment('User pengawas yang memberikan feedback');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\User::class, 'supervisor_id');
            $table->dropColumn(['supervisor_feedback', 'supervisor_action_plan', 'supervisor_priority', 'supervisor_id']);
        });
    }
};
