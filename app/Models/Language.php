<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'flag_url',
        'is_popular',
        'status',
        'description',
    ];

    /**
     * Get the lessons for this language
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Get the users learning this language
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'learning_language_id');
    }
}
