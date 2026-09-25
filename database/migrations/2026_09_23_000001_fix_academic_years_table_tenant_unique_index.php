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
        Schema::table('academic_years', function (Blueprint $table) {
            // Drop global unique index on year if exists
            try {
                $table->dropUnique('academic_years_year_unique');
            } catch (\Throwable $e) {
                // Ignore if already dropped
            }

            // Add composite unique index scoped per school_id
            $table->unique(['school_id', 'year'], 'academic_years_school_id_year_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {
            $table->dropUnique('academic_years_school_id_year_unique');

            $table->unique('year', 'academic_years_year_unique');
        });
    }
};

