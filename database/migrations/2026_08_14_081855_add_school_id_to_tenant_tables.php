<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $tables = [
        'users',
        'teachers',
        'students',
        'parents',
        'wakas',
        'pengawas',
        'kepala_sekolahs',
        'classes',
        'subjects',
        'academic_years',
        'semesters',
        'teacher_subjects',
        'student_classes',
        'settings'
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // PHASE A: Add nullable school_id
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('school_id')->nullable()->constrained('schools')->restrictOnDelete();
            });
        }

        // PHASE B: Backfill existing data
        $school = DB::table('schools')->orderBy('id')->first();
        if ($school) {
            foreach ($this->tables as $tableName) {
                DB::table($tableName)->whereNull('school_id')->update(['school_id' => $school->id]);
            }
        }

        // PHASE C & D: Verify and Change to NOT NULL
        foreach ($this->tables as $tableName) {
            $nullCount = DB::table($tableName)->whereNull('school_id')->count();
            if ($nullCount > 0) {
                throw new \Exception("Cannot make school_id NOT NULL on {$tableName} because there are {$nullCount} NULL records remaining.");
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('school_id')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (array_reverse($this->tables) as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }
    }
};
