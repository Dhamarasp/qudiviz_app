<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'icon',
        'type',
        'order',
        'status',
        'duration',
    ];

    /**
     * Get the lesson that this sub-lesson belongs to
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the content for this sub-lesson
     */
    public function content(): HasMany
    {
        return $this->hasMany(SubLessonContent::class);
    }

    /**
     * Get the user progress for this sub-lesson
     */
    public function userProgress(): HasMany
    {
        return $this->hasMany(UserSubLessonProgress::class);
    }
}
