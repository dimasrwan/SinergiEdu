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
        Schema::table('learning_meetings', function (Blueprint $table) {
            $table->string('material_file_path')->nullable();
            $table->string('assignment_file_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_meetings', function (Blueprint $table) {
            $table->dropColumn(['material_file_path', 'assignment_file_path']);
        });
    }
};
