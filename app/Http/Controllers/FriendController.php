<?php

namespace App\Http\Controllers;

use App\Helpers\ToastHelper;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    /**
     * Display a listing of the user's friends.
     */
    public function index()
    {
        $user = Auth::user();
        $friends = $user->friends();
        $pendingRequests = $user->friendRequests();
        $sentRequests = $user->pendingFriendRequests();
        
        return view('friends.index', compact('friends', 'pendingRequests', 'sentRequests'));
    }
    
    /**
     * Search for users to add as friends.
     */
    public function search(Request $request)
    {

        $query = $request->input('query');
        $currentUser = Auth::user();
        
        $users = User::where('id', '!=', $currentUser->id)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('username', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get();
            
        // Add friendship status for each user
        foreach ($users as $user) {
            $user->friendship_status = $currentUser->friendshipStatusWith($user);
        }
        
        return view('friends.search', compact('users', 'query'));
    }
    
    /**
     * Send a friend request.
     */
    public function sendRequest(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Check if users are already friends
        if ($currentUser->isFriendWith($user)) {
            ToastHelper::error('You are already friends with this user.');
            return redirect()->back();
        }
        
        // Check if there's already a pending request
        if ($currentUser->hasSentFriendRequestTo($user)) {
            ToastHelper::info('You have already sent a friend request to this user.');
            return redirect()->back();
        }
        
        // Check if the other user has already sent a request
        if ($currentUser->hasFriendRequestFrom($user)) {
            // Accept their request instead
            $friendship = Friendship::where('user_id', $user->id)
                ->where('friend_id', $currentUser->id)
                ->first();
                
            $friendship->status = 'accepted';
            $friendship->save();
            
            ToastHelper::success('Friend request accepted!');
            return redirect()->back();
        }
        
        // Create a new friendship request
        Friendship::create([
            'user_id' => $currentUser->id,
            'friend_id' => $user->id,
            'status' => 'pending'
        ]);
        
        ToastHelper::success('Friend request sent!');
        return redirect()->back();
    }
    
    /**
     * Accept a friend request.
     */
    public function acceptRequest(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Find the friendship
        $friendship = Friendship::where('user_id', $user->id)
            ->where('friend_id', $currentUser->id)
            ->where('status', 'pending')
            ->first();
            
        if (!$friendship) {
            ToastHelper::error('Friend request not found.');
            return redirect()->back();
        }
        
        // Update status to accepted
        $friendship->status = 'accepted';
        $friendship->save();
        
        ToastHelper::success('Friend request accepted!');
        return redirect()->back();
    }
    
    /**
     * Reject a friend request.
     */
    public function rejectRequest(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Find the friendship
        $friendship = Friendship::where('user_id', $user->id)
            ->where('friend_id', $currentUser->id)
            ->where('status', 'pending')
            ->first();
            
        if (!$friendship) {
            ToastHelper::error('Friend request not found.');
            return redirect()->back();
        }
        
        // Update status to rejected
        $friendship->status = 'rejected';
        $friendship->save();
        
        ToastHelper::success('Friend request rejected.');
        return redirect()->back();
    }
    
    /**
     * Cancel a sent friend request.
     */
    public function cancelRequest(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Find the friendship
        $friendship = Friendship::where('user_id', $currentUser->id)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->first();
            
        if (!$friendship) {
            ToastHelper::error('Friend request not found.');
            return redirect()->back();
        }
        
        // Delete the friendship
        $friendship->delete();
        
        ToastHelper::success('Friend request cancelled.');
        return redirect()->back();
    }
    
    /**
     * Remove a friend.
     */
    public function removeFriend(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Find the friendship (either direction)
        $friendship1 = Friendship::where('user_id', $currentUser->id)
            ->where('friend_id', $user->id)
            ->first();
            
        $friendship2 = Friendship::where('user_id', $user->id)
            ->where('friend_id', $currentUser->id)
            ->first();
            
        // Delete the friendship
        if ($friendship1) {
            $friendship1->delete();
        }
        
        if ($friendship2) {
            $friendship2->delete();
        }
        
        if (!$friendship1 && !$friendship2) {
            ToastHelper::error('Friendship not found.');
            return redirect()->back();
        }
        
        ToastHelper::success('Friend removed.');
        return redirect()->back();
    }
    
    /**
     * Block a user.
     */
    public function blockUser(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Delete any existing friendships
        Friendship::where(function($query) use ($currentUser, $user) {
            $query->where('user_id', $currentUser->id)
                  ->where('friend_id', $user->id);
        })->orWhere(function($query) use ($currentUser, $user) {
            $query->where('user_id', $user->id)
                  ->where('friend_id', $currentUser->id);
        })->delete();
        
        // Create a new blocked relationship
        Friendship::create([
            'user_id' => $currentUser->id,
            'friend_id' => $user->id,
            'status' => 'blocked'
        ]);
        
        ToastHelper::success('User blocked.');
        return redirect()->back();
    }
    
    /**
     * Unblock a user.
     */
    public function unblockUser(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Find the block relationship
        $friendship = Friendship::where('user_id', $currentUser->id)
            ->where('friend_id', $user->id)
            ->where('status', 'blocked')
            ->first();
            
        if (!$friendship) {
            ToastHelper::error('Block relationship not found.');
            return redirect()->back();
        }
        
        // Delete the block
        $friendship->delete();
        
        ToastHelper::success('User unblocked.');
        return redirect()->back();
    }
    
    /**
     * View blocked users.
     */
    public function blocked()
    {
        $user = Auth::user();
        $blockedUsers = $user->friendships()
            ->where('status', 'blocked')
            ->with('friend')
            ->get()
            ->pluck('friend');
            
        return view('friends.blocked', compact('blockedUsers'));
    }
}
