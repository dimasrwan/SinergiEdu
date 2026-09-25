<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->after('supervisor_id');
        });

        Schema::table('school_evaluations', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->after('content');
        });
    }

    public function down(): void
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->dropColumn('is_archived');
        });

        Schema::table('school_evaluations', function (Blueprint $table) {
            $table->dropColumn('is_archived');
        });
    }
};