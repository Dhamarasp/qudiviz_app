<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Unit;
use App\Models\UserLessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Display the lessons page
     */
    public function index()
    {
        $user = Auth::user();
        $language = $user->learningLanguage;

        if (!$language) {
            return redirect()->route('switch-language');
        }

        // Get units with lessons for the current language
        $units = Unit::with(['lessons' => function ($query) {
            $query->orderBy('order');
        }])
        ->where('language_id', $language->id)
        ->orderBy('order')
        ->get();

        // Get user's lesson progress
        $userProgress = UserLessonProgress::where('user_id', $user->id)
            ->get()
            ->keyBy('lesson_id');

        return view('lessons.index', [
            'language' => $language,
            'units' => $units,
            'userProgress' => $userProgress,
        ]);
    }

    /**
     * Mark a lesson as started
     */
    public function startLesson(Request $request, Lesson $lesson)
    {
        $user = Auth::user();

        // Check if user already has progress for this lesson
        $progress = UserLessonProgress::firstOrNew([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
        ]);

        // Only update if not already completed
        if ($progress->status !== 'completed') {
            $progress->status = 'in_progress';
            $progress->save();
        }

        return redirect()->route('lessons.sub', ['lesson_id' => $lesson->id]);
    }
}
