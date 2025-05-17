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
        Schema::create('user_practice_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->integer('total_practice_sessions')->default(0);
            $table->integer('total_practice_time_seconds')->default(0);
            $table->integer('total_questions_answered')->default(0);
            $table->integer('total_correct_answers')->default(0);
            $table->integer('total_xp_earned')->default(0);
            $table->json('weekly_practice_data')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_practice_stats');
    }
};
