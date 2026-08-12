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
        \App\Models\Role::firstOrCreate(
            ['name' => 'kepala_sekolah'],
            ['display_name' => 'Kepala Sekolah/Madrasah']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\Role::where('name', 'kepala_sekolah')->delete();
    }
};
