<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_id',
        'question_type',
        'question_text',
        'difficulty_level',
        'hint',
        'explanation',
        'category',
        'status',
    ];

    /**
     * Get the language that this question belongs to
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Get the answers for this question
     */
    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    /**
     * Get the correct answer for this question
     */
    public function correctAnswer()
    {
        return $this->answers()->where('is_correct', true)->first();
    }

    /**
     * Get practice questions that use this quiz question
     */
    public function practiceQuestions(): HasMany
    {
        return $this->hasMany(PracticeQuestion::class);
    }
}
