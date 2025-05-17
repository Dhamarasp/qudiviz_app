<?php

namespace App\Http\Controllers;

use App\Models\PracticeQuestion;
use App\Models\PracticeSession;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Display the quiz page for a practice session
     */
    public function index(Request $request)
    {
        $sessionId = $request->query('session_id');
        
        if (!$sessionId) {
            // If no session ID provided, redirect to practice page
            return redirect()->route('practice.index');
        }
        
        $session = PracticeSession::findOrFail($sessionId);
        $user = Auth::user();
        
        // Ensure the session belongs to the user
        if ($session->user_id !== $user->id) {
            abort(403);
        }
        
        // Get the current question
        $currentQuestionOrder = $request->query('question', 1);
        $practiceQuestion = $session->questions()
            ->where('order', $currentQuestionOrder)
            ->firstOrFail();
        
        // Load the quiz question with answers
        $practiceQuestion->load('quizQuestion.answers');
        
        // Calculate progress
        $progress = ($currentQuestionOrder / $session->total_questions) * 100;
        
        return view('quiz.index', [
            'session' => $session,
            'practiceQuestion' => $practiceQuestion,
            'currentQuestionOrder' => $currentQuestionOrder,
            'totalQuestions' => $session->total_questions,
            'progress' => $progress,
        ]);
    }
    
    /**
     * Submit an answer for a quiz question
     */
    public function submitAnswer(Request $request)
    {
        $request->validate([
            'practice_question_id' => 'required|exists:practice_questions,id',
            'answer_id' => 'required|exists:quiz_answers,id',
        ]);
        
        $practiceQuestionId = $request->practice_question_id;
        $answerId = $request->answer_id;
        
        $practiceQuestion = PracticeQuestion::findOrFail($practiceQuestionId);
        $session = $practiceQuestion->practiceSession;
        $user = Auth::user();
        
        // Ensure the session belongs to the user
        if ($session->user_id !== $user->id) {
            abort(403);
        }
        
        // Check if the question has already been answered
        if ($practiceQuestion->answered_correctly !== null) {
            return response()->json([
                'error' => 'This question has already been answered.',
            ], 400);
        }
        
        // Load the quiz question with answers
        $quizQuestion = $practiceQuestion->quizQuestion;
        $quizQuestion->load('answers');
        
        // Find the selected answer
        $selectedAnswer = $quizQuestion->answers->find($answerId);
        
        if (!$selectedAnswer) {
            return response()->json([
                'error' => 'Invalid answer selected.',
            ], 400);
        }
        
        // Check if the answer is correct
        $isCorrect = $selectedAnswer->is_correct;
        
        // Update the practice question
        $practiceQuestion->update([
            'answered_correctly' => $isCorrect,
            'user_answer' => $selectedAnswer->answer_text,
        ]);
        
        // If correct, increment the correct answers count for the session
        if ($isCorrect) {
            $session->increment('correct_answers');
        }
        
        // Determine the next question
        $nextQuestionOrder = $practiceQuestion->order + 1;
        $isLastQuestion = $nextQuestionOrder > $session->total_questions;
        
        // Prepare response data
        $responseData = [
            'is_correct' => $isCorrect,
            'correct_answer' => $quizQuestion->correctAnswer()->answer_text,
            'explanation' => $quizQuestion->explanation,
            'is_last_question' => $isLastQuestion,
        ];
        
        if (!$isLastQuestion) {
            $responseData['next_question_url'] = route('practice.quiz', [
                'session_id' => $session->id,
                'question' => $nextQuestionOrder,
            ]);
        } else {
            // Fix: Use the correct parameter name that matches the route definition
            $responseData['complete_url'] = route('practice.complete', ['session' => $session->id]);
        }
        
        return response()->json($responseData);
    }
    
    /**
     * Skip a question in the quiz
     */
    public function skipQuestion(Request $request)
    {
        $request->validate([
            'practice_question_id' => 'required|exists:practice_questions,id',
        ]);
        
        $practiceQuestionId = $request->practice_question_id;
        
        $practiceQuestion = PracticeQuestion::findOrFail($practiceQuestionId);
        $session = $practiceQuestion->practiceSession;
        $user = Auth::user();
        
        // Ensure the session belongs to the user
        if ($session->user_id !== $user->id) {
            abort(403);
        }
        
        // Mark the question as incorrect (skipped)
        $practiceQuestion->update([
            'answered_correctly' => false,
            'user_answer' => 'skipped',
        ]);
        
        // Determine the next question
        $nextQuestionOrder = $practiceQuestion->order + 1;
        $isLastQuestion = $nextQuestionOrder > $session->total_questions;
        
        if ($isLastQuestion) {
            return redirect()->route('practice.complete', ['session' => $session->id]);
        }
        
        return redirect()->route('practice.quiz', [
            'session_id' => $session->id,
            'question' => $nextQuestionOrder,
        ]);
    }
}
