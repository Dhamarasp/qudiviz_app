@extends('app')
@section('title', 'Practice Results')

@section('content')
    <!-- Results Header -->
    <div class="bg-[#18AEB5] text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Practice Results</h1>
                    <p class="text-[#99E5E0]">See how you did in your practice session</p>
                </div>
                <a href="{{ route('practice.index') }}" class="bg-white text-[#18AEB5] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                    Back to Practice
                </a>
            </div>
        </div>
    </div>

    <!-- Results Content -->
    <div class="max-w-3xl mx-auto px-4 py-8">
        <!-- Score Summary -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold mb-2">Your Score</h2>
                <div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-[#99E5E0] mb-4">
                    <span class="text-4xl font-bold text-[#004A68]">{{ $session->accuracy_percentage }}%</span>
                </div>
                <p class="text-gray-600">You got {{ $session->correct_answers }} out of {{ $session->total_questions }} questions correct</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">XP Earned</p>
                    <p class="text-2xl font-bold text-[#18AEB5]">+{{ $session->xp_earned }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">Time Spent</p>
                    <p class="text-2xl font-bold text-[#18AEB5]">
                        @php
                            $minutes = floor($session->duration_seconds / 60);
                            $seconds = $session->duration_seconds % 60;
                        @endphp
                        {{ $minutes }}:{{ str_pad($seconds, 2, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">Practice Type</p>
                    <p class="text-2xl font-bold text-[#18AEB5]">{{ ucfirst($session->practice_type) }}</p>
                </div>
            </div>
        </div>

        <!-- Question Review -->
        <h2 class="text-xl font-bold mb-4">Question Review</h2>

        <div class="space-y-6">
            @foreach($session->questions as $index => $question)
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                                @if($question->answered_correctly)
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-green-500"></i>
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-times text-red-500"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <h3 class="font-bold mb-2">Question {{ $index + 1 }}: {{ $question->quizQuestion->question_text }}</h3>
                                
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500 mb-1">Your answer:</p>
                                    <p class="font-medium {{ $question->answered_correctly ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $question->user_answer ?: 'Skipped' }}
                                    </p>
                                </div>
                                
                                @if(!$question->answered_correctly)
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-500 mb-1">Correct answer:</p>
                                        <p class="font-medium text-green-600">
                                            {{ $question->quizQuestion->correctAnswer()->answer_text }}
                                        </p>
                                    </div>
                                @endif
                                
                                @if($question->quizQuestion->explanation)
                                    <div class="bg-gray-50 p-3 rounded-md">
                                        <p class="text-sm">{{ $question->quizQuestion->explanation }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('practice.index') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-300 text-center">
                Back to Practice
            </a>
            <form action="{{ route('practice.start') }}" method="POST">
                @csrf
                <input type="hidden" name="practice_type" value="{{ $session->practice_type }}">
                <input type="hidden" name="num_questions" value="{{ $session->total_questions }}">
                <button type="submit" class="bg-[#18AEB5] text-white px-6 py-3 rounded-xl font-bold hover:bg-[#006B87] w-full">
                    Practice Again
                </button>
            </form>
        </div>
    </div>
@endsection
