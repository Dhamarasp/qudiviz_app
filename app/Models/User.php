<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'bio',
        'profile_image',
        'cover_image',
        'theme_color',
        'status_message',
        'badges',
        'show_xp_in_profile',
        'show_streak_in_profile',
        'show_level_in_profile',
        'learning_language_id',
        'level',
        'xp_points',
        'streak_days',
        'longest_streak_days',
        'last_activity_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity_date' => 'date',
            'badges' => 'array',
            'show_xp_in_profile' => 'boolean',
            'show_streak_in_profile' => 'boolean',
            'show_level_in_profile' => 'boolean',
        ];
    }

    /**
     * Get the language that the user is learning
     */
    public function learningLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'learning_language_id');
    }

    /**
     * Get the lesson progress for this user
     */
    public function lessonProgress(): HasMany
    {
        return $this->hasMany(UserLessonProgress::class);
    }

    /**
     * Get the sub-lesson progress for this user
     */
    public function subLessonProgress(): HasMany
    {
        return $this->hasMany(UserSubLessonProgress::class);
    }
    
    /**
     * Get the user's profile image URL
     */
    public function getProfileImageUrlAttribute()
    {
        if (!$this->profile_image) {
            return null;
        }
        
        return asset('storage/profile_images/' . $this->profile_image);
    }
    
    /**
     * Get the user's cover image URL
     */
    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) {
            return null;
        }
        
        return asset('storage/cover_images/' . $this->cover_image);
    }
    
    /**
     * Get the user's display name (username or name)
     */
    public function getDisplayNameAttribute()
    {
        return $this->username ?? $this->name;
    }
    
    /**
     * Get the user's initials (for avatar)
     */
    public function getInitialsAttribute()
    {
        return strtoupper(substr($this->name, 0, 1));
    }
    
    /**
     * Get friendships that the user initiated
     */
    public function friendships(): HasMany
    {
        return $this->hasMany(Friendship::class, 'user_id');
    }
    
    /**
     * Get friendships where the user is the friend
     */
    public function friendOf(): HasMany
    {
        return $this->hasMany(Friendship::class, 'friend_id');
    }
    
    /**
     * Get all friends (accepted friendships)
     */
    public function friends()
    {
        // Get friends where user initiated the friendship
        $friends1 = $this->friendships()
            ->where('status', 'accepted')
            ->with('friend')
            ->get()
            ->pluck('friend');
            
        // Get friends where user was the recipient
        $friends2 = $this->friendOf()
            ->where('status', 'accepted')
            ->with('user')
            ->get()
            ->pluck('user');
            
        // Combine both collections
        return $friends1->merge($friends2);
    }
    
    /**
     * Get pending friend requests sent by the user
     */
    public function pendingFriendRequests()
    {
        return $this->friendships()
            ->where('status', 'pending')
            ->with('friend')
            ->get();
    }
    
    /**
     * Get pending friend requests received by the user
     */
    public function friendRequests()
    {
        return $this->friendOf()
            ->where('status', 'pending')
            ->with('user')
            ->get();
    }
    
    /**
     * Check if users are friends
     */
    public function isFriendWith(User $user)
    {
        // Check if there's a friendship where this user initiated
        $friendship1 = $this->friendships()
            ->where('friend_id', $user->id)
            ->where('status', 'accepted')
            ->exists();
            
        // Check if there's a friendship where this user is the friend
        $friendship2 = $this->friendOf()
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->exists();
            
        return $friendship1 || $friendship2;
    }
    
    /**
     * Check if user has a pending friend request from another user
     */
    public function hasFriendRequestFrom(User $user)
    {
        return $this->friendOf()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }
    
    /**
     * Check if user has sent a friend request to another user
     */
    public function hasSentFriendRequestTo(User $user)
    {
        return $this->friendships()
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }
    
    /**
     * Get friendship status with another user
     */
    public function friendshipStatusWith(User $user)
    {
        // Check if there's a friendship where this user initiated
        $friendship1 = $this->friendships()
            ->where('friend_id', $user->id)
            ->first();
            
        // Check if there's a friendship where this user is the friend
        $friendship2 = $this->friendOf()
            ->where('user_id', $user->id)
            ->first();
            
        if ($friendship1) {
            return $friendship1->status;
        }
        
        if ($friendship2) {
            return $friendship2->status;
        }
        
        return null;
    }
}
