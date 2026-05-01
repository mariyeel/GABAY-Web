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
        Schema::create('pairings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vi_user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('caregiver_user_id')->constrained('users', 'user_id')->onDelete('cascade');

            $table->string('status')->default('active');
            $table->timestamp('paired_at')->nullable();
            $table->timestamp('unpaired_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pairings');
    }
};
