<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubLessonContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_lesson_id',
        'content_type',
        'content',
        'order',
        'youtube_video_id',
        'thumbnail_url',
        'duration',
    ];

    /**
     * Get the sub-lesson that this content belongs to
     */
    public function subLesson(): BelongsTo
    {
        return $this->belongsTo(SubLesson::class);
    }
}
