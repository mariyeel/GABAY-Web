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
        Schema::create('navigation_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');

            $table->string('origin');
            $table->string('destination');

            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();

            $table->string('status')->default('ongoing');
            // ongoing, completed, interrupted

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigation_sessions');
    }
};
