<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPracticeStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'language_id',
        'total_practice_sessions',
        'total_practice_time_seconds',
        'total_questions_answered',
        'total_correct_answers',
        'total_xp_earned',
        'weekly_practice_data',
    ];

    protected $casts = [
        'weekly_practice_data' => 'array',
    ];

    /**
     * Get the user that owns these stats
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the language for these stats
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Calculate the accuracy percentage
     */
    public function getAccuracyPercentageAttribute()
    {
        if ($this->total_questions_answered === 0) {
            return 0;
        }
        
        return round(($this->total_correct_answers / $this->total_questions_answered) * 100);
    }

    /**
     * Get the total practice time in hours
     */
    public function getTotalPracticeTimeHoursAttribute()
    {
        return round($this->total_practice_time_seconds / 3600, 1);
    }
}
