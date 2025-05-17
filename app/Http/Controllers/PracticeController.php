<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\PracticeCategory;
use App\Models\PracticeSession;
use App\Models\QuizQuestion;
use App\Models\UserPracticeStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PracticeController extends Controller
{
    /**
     * Display the practice index page
     */
    public function index()
    {
        $user = Auth::user();
        $language = $user->learningLanguage;
        
        // Get practice categories
        $categories = PracticeCategory::where('status', 'active')->get();
        
        // Get user practice stats
        $stats = UserPracticeStat::firstOrCreate(
            ['user_id' => $user->id, 'language_id' => $language->id],
            [
                'total_practice_sessions' => 0,
                'total_practice_time_seconds' => 0,
                'total_questions_answered' => 0,
                'total_correct_answers' => 0,
                'total_xp_earned' => 0,
                'weekly_practice_data' => $this->initializeWeeklyData(),
            ]
        );
        
        return view('practice.index', [
            'categories' => $categories,
            'stats' => $stats,
        ]);
    }
    
    /**
     * Start a new practice session
     */
    public function startSession(Request $request)
    {
        $request->validate([
            'practice_type' => 'required|string',
            'num_questions' => 'sometimes|integer|min:5|max:20',
        ]);
        
        $user = Auth::user();
        $language = $user->learningLanguage;
        
        if (!$language) {
            return redirect()->route('switch-language');
        }
        
        $practiceType = $request->practice_type;
        $numQuestions = $request->num_questions ?? 10;
        
        // Get questions based on practice type
        $questions = $this->getQuestionsForPractice($practiceType, $language->id, $numQuestions);
        
        if ($questions->isEmpty()) {
            return back()->with('error', 'No questions available for this practice type.');
        }
        
        // Create a new practice session
        $session = PracticeSession::create([
            'user_id' => $user->id,
            'language_id' => $language->id,
            'practice_type' => $practiceType,
            'status' => 'in_progress',
            'total_questions' => $questions->count(),
            'correct_answers' => 0,
            'xp_earned' => 0,
            'started_at' => now(),
        ]);
        
        // Add questions to the session
        foreach ($questions as $index => $question) {
            $session->questions()->create([
                'quiz_question_id' => $question->id,
                'order' => $index + 1,
            ]);
        }
        
        return redirect()->route('practice.quiz', ['session_id' => $session->id]);
    }
    
    /**
     * Complete a practice session
     */
    public function completeSession(Request $request, PracticeSession $session)
    {
        $user = Auth::user();
        
        // Ensure the session belongs to the user
        if ($session->user_id !== $user->id) {
            abort(403);
        }
        
        // Calculate XP earned (10 XP per correct answer)
        $xpEarned = $session->correct_answers * 10;
        
        // Update session
        $session->update([
            'status' => 'completed',
            'xp_earned' => $xpEarned,
            'completed_at' => now(),
        ]);
        
        // Update user XP
        $user->increment('xp_points', $xpEarned);
        
        // Update streak if needed
        $today = now()->format('Y-m-d');
        if ($user->last_activity_date !== $today) {
            $user->increment('streak_days');
            $user->last_activity_date = $today;
            $user->save();
        }
        
        // Update user practice stats
        $this->updateUserPracticeStats($session);
        
        // Fix: Make sure we're using the correct parameter name
        return redirect()->route('practice.results', ['session' => $session->id]);
    }
    
    /**
     * Show practice session results
     */
    public function showResults(PracticeSession $session)
    {
        $user = Auth::user();
        
        // Ensure the session belongs to the user
        if ($session->user_id !== $user->id) {
            abort(403);
        }
        
        // Load questions with quiz questions and answers
        $session->load(['questions.quizQuestion.answers']);
        
        return view('practice.results', [
            'session' => $session,
        ]);
    }
    
    /**
     * Get questions for a practice session based on type
     */
    private function getQuestionsForPractice($practiceType, $languageId, $numQuestions)
    {
        $user = Auth::user();
        
        switch ($practiceType) {
            case 'daily':
                // Get a mix of questions for daily practice
                return QuizQuestion::where('language_id', $languageId)
                    ->where('status', 'active')
                    ->inRandomOrder()
                    ->limit($numQuestions)
                    ->get();
                
            case 'weak_skills':
                // Get questions from categories where the user has performed poorly
                $weakCategories = DB::table('practice_questions')
                    ->join('practice_sessions', 'practice_questions.practice_session_id', '=', 'practice_sessions.id')
                    ->join('quiz_questions', 'practice_questions.quiz_question_id', '=', 'quiz_questions.id')
                    ->where('practice_sessions.user_id', $user->id)
                    ->where('practice_sessions.language_id', $languageId)
                    ->where('practice_questions.answered_correctly', false)
                    ->select('quiz_questions.category')
                    ->groupBy('quiz_questions.category')
                    ->orderByRaw('COUNT(*) DESC')
                    ->limit(3)
                    ->pluck('category');
                
                if ($weakCategories->isEmpty()) {
                    // Fallback to random questions if no weak categories found
                    return QuizQuestion::where('language_id', $languageId)
                        ->where('status', 'active')
                        ->inRandomOrder()
                        ->limit($numQuestions)
                        ->get();
                }
                
                return QuizQuestion::where('language_id', $languageId)
                    ->where('status', 'active')
                    ->whereIn('category', $weakCategories)
                    ->inRandomOrder()
                    ->limit($numQuestions)
                    ->get();
                
            default:
                // For specific categories (grammar, vocabulary, etc.)
                return QuizQuestion::where('language_id', $languageId)
                    ->where('status', 'active')
                    ->where('category', $practiceType)
                    ->inRandomOrder()
                    ->limit($numQuestions)
                    ->get();
        }
    }
    
    /**
     * Update user practice statistics
     */
    private function updateUserPracticeStats(PracticeSession $session)
    {
        $stats = UserPracticeStat::firstOrCreate(
            ['user_id' => $session->user_id, 'language_id' => $session->language_id],
            [
                'total_practice_sessions' => 0,
                'total_practice_time_seconds' => 0,
                'total_questions_answered' => 0,
                'total_correct_answers' => 0,
                'total_xp_earned' => 0,
                'weekly_practice_data' => $this->initializeWeeklyData(),
            ]
        );
        
        // Update totals
        $stats->increment('total_practice_sessions');
        $stats->increment('total_questions_answered', $session->total_questions);
        $stats->increment('total_correct_answers', $session->correct_answers);
        $stats->increment('total_xp_earned', $session->xp_earned);
        
        // Update practice time if session is completed
        if ($session->completed_at) {
            $durationSeconds = $session->duration_seconds;
            $stats->increment('total_practice_time_seconds', $durationSeconds);
        }
        
        // Update weekly data
        $weeklyData = $stats->weekly_practice_data ?? $this->initializeWeeklyData();
        $dayOfWeek = now()->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
        
        // Adjust to make Monday index 0
        $dayIndex = ($dayOfWeek === 0) ? 6 : $dayOfWeek - 1;
        
        $weeklyData[$dayIndex] += $session->total_questions;
        
        $stats->weekly_practice_data = $weeklyData;
        $stats->save();
    }
    
    /**
     * Initialize weekly practice data array
     */
    private function initializeWeeklyData()
    {
        return [0, 0, 0, 0, 0, 0, 0]; // Mon, Tue, Wed, Thu, Fri, Sat, Sun
    }
}
