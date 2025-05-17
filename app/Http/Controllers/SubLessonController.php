<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\SubLesson;
use App\Models\UserLessonProgress;
use App\Models\UserSubLessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubLessonController extends Controller
{
    /**
     * Display the sub-lessons for a lesson
     */
    public function index(Request $request)
    {
        $lessonId = $request->query('lesson_id');
        
        if (!$lessonId) {
            return redirect()->route('lessons');
        }
        
        $lesson = Lesson::findOrFail($lessonId);
        $user = Auth::user();

        // Get sub-lessons for this lesson
        $subLessons = SubLesson::where('lesson_id', $lesson->id)
            ->orderBy('order')
            ->get();

        // Get user's sub-lesson progress
        $userProgress = UserSubLessonProgress::where('user_id', $user->id)
            ->whereIn('sub_lesson_id', $subLessons->pluck('id'))
            ->get()
            ->keyBy('sub_lesson_id');

        // Get lesson progress
        $lessonProgress = UserLessonProgress::firstOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['status' => 'not_started']
        );

        // If not already in progress, mark as in progress
        if ($lessonProgress->status === 'not_started') {
            $lessonProgress->status = 'in_progress';
            $lessonProgress->save();
        }

        // Get unit information
        $unit = $lesson->unit;
        $unitLessons = $unit->lessons()->orderBy('order')->get();
        $lessonPosition = $unitLessons->search(function($item) use ($lesson) {
            return $item->id === $lesson->id;
        });
        $lessonNumber = $lessonPosition !== false ? $lessonPosition + 1 : null;
        $totalLessons = $unitLessons->count();

        return view('sub-lessons.index', [
            'lesson' => $lesson,
            'subLessons' => $subLessons,
            'userProgress' => $userProgress,
            'unit' => $unit,
            'lessonNumber' => $lessonNumber,
            'totalLessons' => $totalLessons,
        ]);
    }

    /**
     * Mark a sub-lesson as started
     */
    public function startSubLesson(Request $request, SubLesson $subLesson)
    {
        $user = Auth::user();

        // Check if user already has progress for this sub-lesson
        $progress = UserSubLessonProgress::firstOrNew([
            'user_id' => $user->id,
            'sub_lesson_id' => $subLesson->id,
        ]);

        // Only update if not already completed
        if ($progress->status !== 'completed') {
            $progress->status = 'in_progress';
            $progress->save();
        }

        // Redirect based on sub-lesson type
        if ($subLesson->type === 'quiz') {
            return redirect()->route('quiz', ['sub_lesson_id' => $subLesson->id]);
        } else {
            return redirect()->route('lessons.sub.study', ['sub_lesson_id' => $subLesson->id]);
        }
    }

    /**
     * Mark a sub-lesson as completed
     */
    public function completeSubLesson(Request $request, SubLesson $subLesson)
    {
        $user = Auth::user();
        $score = $request->input('score', null);

        // Update sub-lesson progress
        UserSubLessonProgress::updateOrCreate(
            ['user_id' => $user->id, 'sub_lesson_id' => $subLesson->id],
            [
                'status' => 'completed',
                'completed_at' => now(),
                'score' => $score,
            ]
        );

        // Check if all sub-lessons for this lesson are completed
        $lesson = $subLesson->lesson;
        $allSubLessons = $lesson->subLessons()->get();
        $totalSubLessons = $allSubLessons->count();
        
        $completedSubLessons = UserSubLessonProgress::where('user_id', $user->id)
            ->whereIn('sub_lesson_id', $allSubLessons->pluck('id'))
            ->where('status', 'completed')
            ->count();
        
        // If all sub-lessons are completed, mark the lesson as completed
        if ($completedSubLessons >= $totalSubLessons) {
            UserLessonProgress::updateOrCreate(
                ['user_id' => $user->id, 'lesson_id' => $lesson->id],
                [
                    'status' => 'completed',
                    'completed_at' => now(),
                ]
            );
            
            // Log for debugging
            \Log::info("Lesson {$lesson->id} marked as completed for user {$user->id}. Completed {$completedSubLessons} of {$totalSubLessons} sub-lessons.");
        } else {
            // Log for debugging
            \Log::info("Lesson {$lesson->id} still in progress for user {$user->id}. Completed {$completedSubLessons} of {$totalSubLessons} sub-lessons.");
        }

        // Award XP points
        $user->increment('xp_points', 20);

        // Update streak
        $today = now()->format('Y-m-d');
        if ($user->last_activity_date !== $today) {
            $user->increment('streak_days');
            $user->last_activity_date = $today;
            $user->save();
        }

        return redirect()->route('lessons.sub', ['lesson_id' => $lesson->id]);
    }
}
