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
        Schema::table('schools', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('npsn')->nullable()->after('name');
            $table->text('address')->nullable()->after('npsn');
            $table->string('phone')->nullable()->after('address');
            $table->string('email')->nullable()->after('phone');
            $table->string('logo')->nullable()->after('email');
            $table->boolean('is_active')->default(true)->after('logo');

            // Add index for npsn
            $table->index('npsn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropIndex(['npsn']);
            $table->dropColumn([
                'name',
                'npsn',
                'address',
                'phone',
                'email',
                'logo',
                'is_active'
            ]);
        });
    }
};
