<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PracticeQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'practice_session_id',
        'quiz_question_id',
        'order',
        'answered_correctly',
        'user_answer',
    ];

    protected $casts = [
        'answered_correctly' => 'boolean',
    ];

    /**
     * Get the practice session that this question belongs to
     */
    public function practiceSession(): BelongsTo
    {
        return $this->belongsTo(PracticeSession::class);
    }

    /**
     * Get the quiz question for this practice question
     */
    public function quizQuestion(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class);
    }
}
