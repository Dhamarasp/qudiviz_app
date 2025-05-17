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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->string('question_type'); // multiple_choice, translation, fill_in_blank, etc.
            $table->text('question_text');
            $table->string('difficulty_level')->default('beginner'); // beginner, intermediate, advanced
            $table->text('hint')->nullable();
            $table->text('explanation')->nullable();
            $table->string('category')->nullable(); // vocabulary, grammar, etc.
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
