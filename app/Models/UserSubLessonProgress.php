<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubLessonProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sub_lesson_id',
        'status',
        'completed_at',
        'score',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns this progress
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sub-lesson that this progress belongs to
     */
    public function subLesson(): BelongsTo
    {
        return $this->belongsTo(SubLesson::class);
    }
}
