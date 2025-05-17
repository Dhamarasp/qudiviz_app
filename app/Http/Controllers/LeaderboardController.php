<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    /**
     * Display the leaderboard
     */
    public function index()
    {
        // Get current user
        $currentUser = Auth::user();
        
        // Get top 10 users by XP points
        $topUsers = User::select('id', 'name', 'username', 'xp_points', 'streak_days', 'learning_language_id', 'profile_image')
            ->where('is_admin', 0)
            ->with('learningLanguage')
            ->orderBy('xp_points', 'desc')
            ->limit(10)
            ->get();
            
        // Get current user's rank
        $currentUserRank = User::where('xp_points', '>', $currentUser->xp_points)->count() + 1;
        
        // Check if current user is in top 10
        $currentUserInTop = $topUsers->contains('id', $currentUser->id);
        
        // If current user is not in top 10, get their data separately
        if (!$currentUserInTop) {
            // Get users around current user (2 above and 2 below)
            $usersAroundCurrent = User::select('id', 'name', 'username', 'xp_points', 'streak_days', 'learning_language_id', 'profile_image')
                ->with('learningLanguage')
                ->where(function($query) use ($currentUser) {
                    $query->where('xp_points', '<=', $currentUser->xp_points)
                          ->where('id', '!=', $currentUser->id);
                })
                ->orderBy('xp_points', 'desc')
                ->limit(2)
                ->get();
                
            $usersAboveCurrent = User::select('id', 'name', 'username', 'xp_points', 'streak_days', 'learning_language_id', 'profile_image')
                ->with('learningLanguage')
                ->where('xp_points', '>', $currentUser->xp_points)
                ->orderBy('xp_points', 'asc')
                ->limit(2)
                ->get()
                ->reverse();
                
            // Add current user to the list
            $currentUserData = User::select('id', 'name', 'username', 'xp_points', 'streak_days', 'learning_language_id', 'profile_image')
                ->with('learningLanguage')
                ->where('id', $currentUser->id)
                ->first();
                
            // Combine the collections
            $nearbyUsers = $usersAboveCurrent->concat([$currentUserData])->concat($usersAroundCurrent);
        } else {
            $nearbyUsers = collect();
        }
        
        // Get today's XP for each user
        // In a real app, you'd have a daily_xp table or similar
        // For now, we'll just use random values
        $topUsers->each(function($user) {
            $user->today_xp = rand(0, 100);
        });
        
        $nearbyUsers->each(function($user) {
            $user->today_xp = rand(0, 100);
        });
        
        // Get current user's today XP
        $currentUser->today_xp = $currentUserInTop 
            ? $topUsers->firstWhere('id', $currentUser->id)->today_xp 
            : $nearbyUsers->firstWhere('id', $currentUser->id)->today_xp;
        
        return view('leaderboard.index', [
            'topUsers' => $topUsers,
            'nearbyUsers' => $nearbyUsers,
            'currentUser' => $currentUser,
            'currentUserRank' => $currentUserRank,
            'currentUserInTop' => $currentUserInTop,
        ]);
    }
}
