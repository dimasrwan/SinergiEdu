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
        Schema::table('assignments', function (Blueprint $table) {
            $table->foreignId('learning_meeting_id')
                ->nullable()
                ->after('subject_id')
                ->constrained('learning_meetings')
                ->nullOnDelete();

            $table->foreignId('material_id')
                ->nullable()
                ->after('learning_meeting_id')
                ->constrained('materials')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['material_id']);
            $table->dropColumn('material_id');

            $table->dropForeign(['learning_meeting_id']);
            $table->dropColumn('learning_meeting_id');
        });
    }
};
