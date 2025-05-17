@extends('app')
@section('title', 'Quiz')

@section('content')
    <!-- Progress Bar -->
    <div class="bg-gray-200 h-2">
        <div class="bg-[#18AEB5] h-2 rounded-r-full" style="width: {{ $progress }}%"></div>
    </div>

    <!-- Quiz Content -->
    <div class="max-w-3xl mx-auto px-4 py-8 pb-24">
        <!-- Question -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8" id="question-container">
            <div class="flex justify-between items-center mb-6">
                <span class="text-sm font-medium text-gray-500">Question {{ $currentQuestionOrder }} of {{ $totalQuestions }}</span>
                <div class="flex items-center">
                    <div class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                        <i class="fas fa-clock mr-1"></i>
                        <span id="timer">0:30</span>
                    </div>
                </div>
            </div>

            <div class="text-center mb-8">
                <h2 class="text-xl font-bold mb-2">{{ $practiceQuestion->quizQuestion->question_type === 'translation' ? 'Choose the correct translation for:' : $practiceQuestion->quizQuestion->question_text }}</h2>
                @if($practiceQuestion->quizQuestion->question_type === 'translation')
                    <p class="text-2xl font-bold text-gray-800">"{{ $practiceQuestion->quizQuestion->question_text }}"</p>
                @endif
            </div>

            <!-- Answer Options -->
            <div class="space-y-4" id="answer-options">
                @foreach($practiceQuestion->quizQuestion->answers as $answer)
                    <button class="answer-option w-full text-left p-4 border-2 border-gray-200 rounded-lg hover:bg-gray-50 transition" 
                            data-answer-id="{{ $answer->id }}">
                        <p class="font-medium">{{ $answer->answer_text }}</p>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Feedback (Initially Hidden) -->
        <div id="feedback-correct" class="hidden bg-[#99E5E0]/20 border-l-4 border-[#18AEB5] p-4 rounded-r-md mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-[#18AEB5] text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-[#004A68] font-medium">Correct!</p>
                    <p class="text-[#006B87] text-sm" id="correct-explanation"></p>
                </div>
            </div>
        </div>

        <div id="feedback-incorrect" class="hidden bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-red-800 font-medium">Incorrect</p>
                    <p class="text-red-700 text-sm" id="incorrect-explanation"></p>
                    <p class="text-red-700 text-sm mt-1" id="correct-answer"></p>
                </div>
            </div>
        </div>

        <!-- Hint -->
        @if($practiceQuestion->quizQuestion->hint)
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h3 class="font-bold mb-4">Helpful Tip</h3>
                <p class="text-gray-700 text-sm">{{ $practiceQuestion->quizQuestion->hint }}</p>
            </div>
        @endif

        <!-- Related Questions -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="font-bold mb-4">You'll Also Learn</h3>

            <div class="space-y-3">
                <div class="flex items-center bg-gray-50 p-3 rounded-md">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                        <span class="text-blue-500 font-bold">1</span>
                    </div>
                    <p class="text-sm">More {{ ucfirst($practiceQuestion->quizQuestion->category) }} concepts</p>
                </div>

                <div class="flex items-center bg-gray-50 p-3 rounded-md">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                        <span class="text-blue-500 font-bold">2</span>
                    </div>
                    <p class="text-sm">Common mistakes to avoid</p>
                </div>

                <div class="flex items-center bg-gray-50 p-3 rounded-md">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                        <span class="text-blue-500 font-bold">3</span>
                    </div>
                    <p class="text-sm">Practice with real-world examples</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4">
        <div class="max-w-3xl mx-auto flex justify-between">
            <form action="{{ route('practice.skip-question') }}" method="POST" id="skip-form">
                @csrf
                <input type="hidden" name="practice_question_id" value="{{ $practiceQuestion->id }}">
                <button type="submit" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-300">
                    <i class="fas fa-forward mr-2"></i>
                    Skip
                </button>
            </form>
            
            <button id="continue-btn" class="hidden bg-[#18AEB5] text-white px-6 py-3 rounded-xl font-bold hover:bg-[#006B87]">
                Continue
                <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const answerOptions = document.querySelectorAll('.answer-option');
            const feedbackCorrect = document.getElementById('feedback-correct');
            const feedbackIncorrect = document.getElementById('feedback-incorrect');
            const correctExplanation = document.getElementById('correct-explanation');
            const incorrectExplanation = document.getElementById('incorrect-explanation');
            const correctAnswer = document.getElementById('correct-answer');
            const continueBtn = document.getElementById('continue-btn');
            const skipForm = document.getElementById('skip-form');
            const timerElement = document.getElementById('timer');
            
            let nextUrl = '';
            let timeLeft = 30;
            
            // Start timer
            const timer = setInterval(function() {
                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    // Auto-submit the skip form when time runs out
                    skipForm.submit();
                }
            }, 1000);
            
            // Handle answer selection
            answerOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Disable all options
                    answerOptions.forEach(opt => {
                        opt.disabled = true;
                        opt.classList.remove('hover:bg-gray-50');
                    });
                    
                    // Highlight selected option
                    this.classList.add('border-[#18AEB5]');
                    this.classList.add('bg-[#99E5E0]/20');
                    
                    // Stop timer
                    clearInterval(timer);
                    
                    // Submit answer
                    submitAnswer(this.dataset.answerId);
                });
            });
            
            // Handle continue button
            continueBtn.addEventListener('click', function() {
                if (nextUrl) {
                    window.location.href = nextUrl;
                }
            });
            
            // Submit answer to server
            function submitAnswer(answerId) {
                fetch('{{ route("practice.submit-answer") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        practice_question_id: '{{ $practiceQuestion->id }}',
                        answer_id: answerId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Show feedback
                    if (data.is_correct) {
                        feedbackCorrect.classList.remove('hidden');
                        correctExplanation.textContent = data.explanation || 'Great job!';
                    } else {
                        feedbackIncorrect.classList.remove('hidden');
                        incorrectExplanation.textContent = data.explanation || 'The answer is incorrect.';
                        correctAnswer.textContent = `The correct answer is: "${data.correct_answer}"`;
                    }
                    
                    // Show continue button
                    continueBtn.classList.remove('hidden');
                    
                    // Set next URL
                    if (data.is_last_question) {
                        nextUrl = data.complete_url;
                        continueBtn.textContent = 'Complete Quiz';
                    } else {
                        nextUrl = data.next_question_url;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    </script>
@endsection
