<?php

namespace App\Http\Controllers;

use App\Helpers\ToastHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember-me');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Update user's streak and last activity date
            $user = Auth::user();
            $today = now()->format('Y-m-d');
            
            if ($user->last_activity_date) {
                $lastActivityDate = \Carbon\Carbon::parse($user->last_activity_date);
                $daysSinceLastActivity = $lastActivityDate->diffInDays(now());
                
                if ($daysSinceLastActivity === 0) {
                    // Already logged in today, do nothing
                } elseif ($daysSinceLastActivity === 1) {
                    // Consecutive day, increment streak
                    $user->streak_days += 1;
                    
                    // Update longest streak if current streak is longer
                    if ($user->streak_days > $user->longest_streak_days) {
                        $user->longest_streak_days = $user->streak_days;
                    }
                } else {
                    // Streak broken, reset to 1
                    $user->streak_days = 1;
                }
            } else {
                // First login, set streak to 1
                $user->streak_days = 1;
                $user->longest_streak_days = 1;
            }
            
            $user->last_activity_date = $today;
            $user->save();
            
            ToastHelper::success('Welcome Back!');
            return redirect()->route('lessons');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'language' => 'required',
            'terms' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'learning_language_id' => $request->language,
            'streak_days' => 1,
            'longest_streak_days' => 1,
            'last_activity_date' => now()->format('Y-m-d'),
        ]);

        // Store selected language in session
        session(['selected_language' => $request->language]);

        Auth::login($user);

        ToastHelper::success('Account created successfully!');
        return redirect()->route('lessons');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        ToastHelper::info('You have been logged out.');
        return redirect()->route('home');
    }
}
