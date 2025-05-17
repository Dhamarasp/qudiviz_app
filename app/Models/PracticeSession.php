<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PracticeSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'language_id',
        'practice_type',
        'status',
        'total_questions',
        'correct_answers',
        'xp_earned',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns this practice session
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the language for this practice session
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Get the questions for this practice session
     */
    public function questions(): HasMany
    {
        return $this->hasMany(PracticeQuestion::class)->orderBy('order');
    }

    /**
     * Calculate the accuracy percentage for this session
     */
    public function getAccuracyPercentageAttribute()
    {
        if ($this->total_questions === 0) {
            return 0;
        }
        
        return round(($this->correct_answers / $this->total_questions) * 100);
    }

    /**
     * Calculate the duration of this session in seconds
     */
    public function getDurationSecondsAttribute()
    {
        if (!$this->completed_at) {
            return null;
        }
        
        return $this->completed_at->diffInSeconds($this->started_at);
    }
}
