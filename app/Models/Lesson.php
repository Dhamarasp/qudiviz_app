<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_id',
        'unit_id',
        'title',
        'description',
        'icon',
        'order',
        'status',
    ];

    /**
     * Get the language that this lesson belongs to
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Get the unit that this lesson belongs to
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the sub-lessons for this lesson
     */
    public function subLessons(): HasMany
    {
        return $this->hasMany(SubLesson::class)->orderBy('order');
    }

    /**
     * Get the user progress for this lesson
     */
    public function userProgress(): HasMany
    {
        return $this->hasMany(UserLessonProgress::class);
    }
}
