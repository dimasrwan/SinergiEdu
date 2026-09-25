<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Role::firstOrCreate(
            ['name' => 'komite'],
            ['display_name' => 'Komite Sekolah']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Role::where('name', 'komite')->delete();
    }
};
