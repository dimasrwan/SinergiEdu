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
        Schema::table('subjects', function (Blueprint $table) {
            // Drop global unique index on code if exists
            try {
                $table->dropUnique('subjects_code_unique');
            } catch (\Throwable $e) {
                // Ignore if already dropped
            }

            // Add composite unique indexes scoped per school_id
            $table->unique(['school_id', 'name']);
            $table->unique(['school_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropUnique(['school_id', 'name']);
            $table->dropUnique(['school_id', 'code']);

            $table->unique('code');
        });
    }
};
