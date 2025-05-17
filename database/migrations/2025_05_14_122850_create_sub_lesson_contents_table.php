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
        Schema::create('sub_lesson_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_lesson_id')->constrained()->onDelete('cascade');
            $table->string('content_type'); // video, text, image, etc.
            $table->text('content')->nullable();
            $table->integer('order')->default(0);
            $table->string('youtube_video_id')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->integer('duration')->nullable(); // in seconds
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_lesson_contents');
    }
};
