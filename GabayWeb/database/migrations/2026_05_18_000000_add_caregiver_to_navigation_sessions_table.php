<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('navigation_sessions', function (Blueprint $table) {
            $table->foreignId('caregiver_user_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users', 'user_id')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('navigation_sessions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('caregiver_user_id');
        });
    }
};
