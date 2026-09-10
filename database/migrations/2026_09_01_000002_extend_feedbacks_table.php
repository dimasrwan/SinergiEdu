<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->foreignId('sender_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->string('recipient_role')->nullable()->after('sender_id');
            $table->foreignId('recipient_id')->nullable()->after('recipient_role')->constrained('users')->nullOnDelete();
            $table->string('category')->default('academic')->after('type');
            $table->string('priority')->default('medium')->after('category');
            $table->string('status')->default('draft')->after('priority');
            $table->text('action_plan')->nullable()->after('status');
            $table->date('action_deadline')->nullable()->after('action_plan');
        });
    }

    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['recipient_id']);
            $table->dropColumn([
                'sender_id', 'recipient_role', 'recipient_id',
                'category', 'priority', 'status', 'action_plan', 'action_deadline',
            ]);
        });
    }
};
