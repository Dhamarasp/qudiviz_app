<?php

namespace App\Http\Controllers;

use App\Models\SubLesson;
use App\Models\SubLessonContent;
use App\Models\UserSubLessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyController extends Controller
{
    /**
     * Display the study page for a sub-lesson
     */
    public function index(Request $request)
    {
        $subLessonId = $request->query('sub_lesson_id');

        if (!$subLessonId) {
            return redirect()->route('lessons');
        }

        $subLesson = SubLesson::findOrFail($subLessonId);
        $user = Auth::user();

        // Get content for this sub-lesson
        $contents = SubLessonContent::where('sub_lesson_id', $subLesson->id)
            ->orderBy('order')
            ->get();

        // Get user's progress for this sub-lesson
        $progress = UserSubLessonProgress::firstOrCreate(
            ['user_id' => $user->id, 'sub_lesson_id' => $subLesson->id],
            ['status' => 'not_started']
        );

        // If not already in progress, mark as in progress
        if ($progress->status === 'not_started') {
            $progress->status = 'in_progress';
            $progress->save();
        }

        // Get lesson information
        $lesson = $subLesson->lesson;

        // Get position information
        $allSubLessons = $lesson->subLessons()->orderBy('order')->get();
        $position = $allSubLessons->search(function ($item) use ($subLesson) {
            return $item->id === $subLesson->id;
        });
        $currentPosition = $position !== false ? $position + 1 : null;
        $totalSubLessons = $allSubLessons->count();

        // Get next and previous sub-lessons
        $prevSubLesson = $position > 0 ? $allSubLessons[$position - 1] : null;
        $nextSubLesson = $position < $totalSubLessons - 1 ? $allSubLessons[$position + 1] : null;

        return view('study.index', [
            'subLesson' => $subLesson,
            'contents' => $contents,
            'progress' => $progress,
            'lesson' => $lesson,
            'currentPosition' => $currentPosition,
            'totalSubLessons' => $totalSubLessons,
            'prevSubLesson' => $prevSubLesson,
            'nextSubLesson' => $nextSubLesson,
        ]);
    }

    /**
     * Mark a study session as completed
     */
    public function complete(Request $request, SubLesson $subLesson)
    {
        $user = Auth::user();

        // Update sub-lesson progress
        UserSubLessonProgress::updateOrCreate(
            ['user_id' => $user->id, 'sub_lesson_id' => $subLesson->id],
            [
                'status' => 'completed',
                'completed_at' => now(),
            ]
        );

        // Award XP points
        $user->increment('xp_points', 10);

        // Update streak
        $today = now()->format('Y-m-d');
        if ($user->last_activity_date !== $today) {
            $user->increment('streak_days');
            $user->last_activity_date = $today;
            $user->save();
        }

        return redirect()->route('practice.quiz', ['sub_lesson_id' => $subLesson->id]);
    }
}
