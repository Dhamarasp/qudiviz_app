<?php

namespace App\Http\Controllers;

use App\Helpers\ToastHelper;
use App\Models\Friendship;
use App\Models\User;
use App\Models\UserLessonProgress;
use App\Models\UserSubLessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display the user's profile
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's learning language
        $language = $user->learningLanguage;
        
        // Get user's lesson progress stats
        $lessonsCompleted = UserLessonProgress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
            
        // Get user's streak data
        $currentStreak = $user->streak_days;
        
        // Get longest streak
        $longestStreak = $user->longest_streak_days;
        
        // Get recent activity
        $recentLessonProgress = UserLessonProgress::with('lesson')
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
            
        $recentSubLessonProgress = UserSubLessonProgress::with('subLesson')
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
            
        // Combine and sort recent activity
        $recentActivity = collect();
        
        foreach ($recentLessonProgress as $progress) {
            $recentActivity->push([
                'type' => 'lesson',
                'title' => $progress->lesson->title,
                'status' => $progress->status,
                'xp_earned' => 20, // Default XP for completing a lesson
                'date' => $progress->updated_at,
            ]);
        }
        
        foreach ($recentSubLessonProgress as $progress) {
            $recentActivity->push([
                'type' => 'sub_lesson',
                'title' => $progress->subLesson->title,
                'status' => $progress->status,
                'xp_earned' => 10, // Default XP for completing a sub-lesson
                'date' => $progress->updated_at,
            ]);
        }
        
        $recentActivity = $recentActivity->sortByDesc('date')->take(4);
        
        // Get friends
        $friends = $user->friends()->take(3);
        
        // Get achievements (in a real app, you'd have an achievements table)
        // For now, we'll just use some dummy data based on user stats
        $achievements = [
            [
                'name' => '7 Day Streak',
                'icon' => 'fas fa-fire',
                'unlocked' => $currentStreak >= 7,
            ],
            [
                'name' => 'Perfect Score',
                'icon' => 'fas fa-star',
                'unlocked' => true, // Assume the user has gotten a perfect score at some point
            ],
            [
                'name' => 'First Lesson',
                'icon' => 'fas fa-book',
                'unlocked' => $lessonsCompleted > 0,
            ],
            [
                'name' => 'Top 10',
                'icon' => 'fas fa-trophy',
                'unlocked' => false, // This would be determined by leaderboard position
            ],
            [
                'name' => '30 Day Streak',
                'icon' => 'fas fa-award',
                'unlocked' => $currentStreak >= 30,
            ],
            [
                'name' => 'Complete Unit',
                'icon' => 'fas fa-graduation-cap',
                'unlocked' => false, // This would be determined by checking if any units are fully completed
            ],
        ];
        
        return view('profile.index', [
            'user' => $user,
            'language' => $language,
            'lessonsCompleted' => $lessonsCompleted,
            'currentStreak' => $currentStreak,
            'longestStreak' => $longestStreak,
            'recentActivity' => $recentActivity,
            'friends' => $friends,
            'achievements' => $achievements,
        ]);
    }

    /**
     * Display another user's profile
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();
        
        // Check if the current user is blocked by the profile user
        $isBlocked = Friendship::where('user_id', $user->id)
            ->where('friend_id', $currentUser->id)
            ->where('status', 'blocked')
            ->exists();
            
        if ($isBlocked) {
            ToastHelper::error('You cannot view this profile.');
            return redirect()->route('home');
        }
        
        // Get user's learning language
        $language = $user->learningLanguage;
        
        // Get user's lesson progress stats
        $lessonsCompleted = UserLessonProgress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
            
        // Get user's streak data
        $currentStreak = $user->streak_days;
        $longestStreak = $user->longest_streak_days;
        
        // Get friendship status
        $friendshipStatus = $currentUser->friendshipStatusWith($user);
        
        // Get achievements
        $achievements = [
            [
                'name' => '7 Day Streak',
                'icon' => 'fas fa-fire',
                'unlocked' => $currentStreak >= 7,
            ],
            [
                'name' => 'Perfect Score',
                'icon' => 'fas fa-star',
                'unlocked' => true,
            ],
            [
                'name' => 'First Lesson',
                'icon' => 'fas fa-book',
                'unlocked' => $lessonsCompleted > 0,
            ],
            [
                'name' => 'Top 10',
                'icon' => 'fas fa-trophy',
                'unlocked' => false,
            ],
            [
                'name' => '30 Day Streak',
                'icon' => 'fas fa-award',
                'unlocked' => $currentStreak >= 30,
            ],
            [
                'name' => 'Complete Unit',
                'icon' => 'fas fa-graduation-cap',
                'unlocked' => false,
            ],
        ];
        
        return view('profile.show', [
            'user' => $user,
            'language' => $language,
            'lessonsCompleted' => $lessonsCompleted,
            'currentStreak' => $currentStreak,
            'longestStreak' => $longestStreak,
            'friendshipStatus' => $friendshipStatus,
            'achievements' => $achievements,
        ]);
    }

    /**
     * Show the form for editing the user's profile
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    
    /**
     * Show the form for customizing the user's profile
     */
    public function customize()
    {
        $user = Auth::user();
        
        // Define available theme colors
        $themeColors = [
            '#18AEB5' => 'Teal (Default)',
            '#3B82F6' => 'Blue',
            '#8B5CF6' => 'Purple',
            '#EC4899' => 'Pink',
            '#EF4444' => 'Red',
            '#F59E0B' => 'Amber',
            '#10B981' => 'Emerald',
            '#6B7280' => 'Gray',
        ];
        
        return view('profile.customize', compact('user', 'themeColors'));
    }
    
    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:150',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Update user data
        $user->name = $request->name;
        
        // Only update username if provided
        if ($request->filled('username')) {
            $user->username = $request->username;
        }
        
        $user->email = $request->email;
        $user->bio = $request->bio;
        
        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && $user->profile_image !== 'default.png') {
                Storage::disk('public')->delete('profile_images/' . $user->profile_image);
            }
            
            // Store new image
            $imageName = time() . '.' . $request->profile_image->extension();
            $request->profile_image->storeAs('profile_images', $imageName, 'public');
            $user->profile_image = $imageName;
        }
        
        $user->save();
        
        ToastHelper::success('Profile updated successfully!');
        return redirect()->route('profile.index');
    }
    
    /**
     * Update the user's profile customization
     */
    public function updateCustomization(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'theme_color' => 'nullable|string|max:7',
            'status_message' => 'nullable|string|max:100',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'show_xp_in_profile' => 'boolean',
            'show_streak_in_profile' => 'boolean',
            'show_level_in_profile' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Update user data
        if ($request->filled('theme_color')) {
            $user->theme_color = $request->theme_color;
        }
        $user->status_message = $request->status_message;
        $user->show_xp_in_profile = $request->has('show_xp_in_profile');
        $user->show_streak_in_profile = $request->has('show_streak_in_profile');
        $user->show_level_in_profile = $request->has('show_level_in_profile');
        
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($user->cover_image) {
                Storage::disk('public')->delete('cover_images/' . $user->cover_image);
            }
            
            // Store new image
            $imageName = time() . '.' . $request->cover_image->extension();
            $request->cover_image->storeAs('cover_images', $imageName, 'public');
            $user->cover_image = $imageName;
        }
        
        $user->save();
        
        ToastHelper::success('Profile customization updated successfully!');
        return redirect()->route('profile.customize');
    }
    
    /**
     * Remove the user's profile image
     */
    public function removeProfileImage()
    {
        $user = Auth::user();
        
        // Delete profile image if it's not the default
        if ($user->profile_image && $user->profile_image !== 'default.png') {
            Storage::disk('public')->delete('profile_images/' . $user->profile_image);
        }
        
        // Reset to default
        $user->profile_image = null;
        $user->save();
        
        ToastHelper::success('Profile image removed successfully!');
        return redirect()->route('profile.edit');
    }
    
    /**
     * Remove the user's cover image
     */
    public function removeCoverImage()
    {
        $user = Auth::user();
        
        // Delete cover image
        if ($user->cover_image) {
            Storage::disk('public')->delete('cover_images/' . $user->cover_image);
        }
        
        // Reset to null
        $user->cover_image = null;
        $user->save();
        
        ToastHelper::success('Cover image removed successfully!');
        return redirect()->route('profile.customize');
    }
    
    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $user = Auth::user();
        
        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput();
        }
        
        // Update password
        $user->password = Hash::make($request->password);
        $user->save();
        
        ToastHelper::success('Password updated successfully!');
        return redirect()->route('profile.index');
    }
}
