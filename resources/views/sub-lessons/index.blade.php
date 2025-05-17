@extends('app')

@section('title', 'Sub Lessons')

@section('content')
    <!-- Lesson Header -->
    <div class="bg-[#18AEB5] text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('lessons') }}" class="mr-3 bg-white bg-opacity-20 p-2 rounded-full hover:bg-opacity-30">
                        <i class="fas fa-arrow-left text-black"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold">{{ $lesson->title }}</h1>
                        <p class="text-[#99E5E0]">Unit {{ $unit->order }} • Lesson {{ $lessonNumber }} of
                            {{ $totalLessons }}</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="bg-white text-[#18AEB5] px-4 py-2 rounded-lg font-bold">
                        <i class="fas fa-star mr-1"></i>
                        <span>
                            @if (isset($userProgress) && count($userProgress) === count($subLessons))
                                Completed
                            @else
                                In Progress
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lesson Description -->
    <div class="max-w-5xl mx-auto px-4 py-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold mb-2">About This Lesson</h2>
            <p class="text-gray-600 mb-4">{{ $lesson->description }}</p>

            <div class="flex flex-wrap gap-3">
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Beginner Friendly</span>
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Vocabulary</span>
                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">Conversation</span>
                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">15 Minutes</span>
            </div>
        </div>
    </div>

    <!-- Sub-Lessons List -->
    <div class="max-w-5xl mx-auto px-4 py-8">
        <h2 class="text-xl font-bold mb-4">Lesson Modules</h2>

        <div class="space-y-4">
            @foreach ($subLessons as $index => $subLesson)
                @php
                    $status = isset($userProgress[$subLesson->id])
                        ? $userProgress[$subLesson->id]->status
                        : 'not_started';
                    $isCompleted = $status === 'completed';
                    $isInProgress = $status === 'in_progress';
                    $isLocked =
                        !$isCompleted &&
                        !$isInProgress &&
                        $index > 0 &&
                        (!isset($userProgress[$subLessons[$index - 1]->id]) ||
                            $userProgress[$subLessons[$index - 1]->id]->status !== 'completed');
                @endphp

                @if ($isCompleted)
                    <!-- Completed Sub-Lesson -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="flex flex-col sm:flex-row">
                            <div class="bg-[#18AEB5] sm:w-2 w-full h-2 sm:h-auto"></div>
                            <div class="p-4 sm:p-6 flex-grow">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-[#99E5E0] rounded-full flex items-center justify-center mr-3">
                                                <i class="{{ $subLesson->icon }} text-[#004A68]"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold">{{ $subLesson->title }}</h3>
                                                <p class="text-sm text-gray-500">{{ $subLesson->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="flex items-center mr-4">
                                            <i class="fas fa-check-circle text-[#18AEB5] mr-1"></i>
                                            <span class="text-sm text-gray-500">Completed</span>
                                        </div>
                                        <form action="{{ route('lessons.sub.start', $subLesson) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300">
                                                Review
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($isInProgress)
                    <!-- Current Sub-Lesson -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border-2 border-yellow-400">
                        <div class="flex flex-col sm:flex-row">
                            <div class="bg-[#006B87] sm:w-2 w-full h-2 sm:h-auto"></div>
                            <div class="p-4 sm:p-6 flex-grow">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-[#99E5E0] rounded-full flex items-center justify-center mr-3">
                                                <i class="{{ $subLesson->icon }} text-[#004A68]"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold">{{ $subLesson->title }}</h3>
                                                <p class="text-sm text-gray-500">{{ $subLesson->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="flex items-center mr-4">
                                            <i class="fas fa-spinner text-[#006B87] mr-1"></i>
                                            <span class="text-sm text-gray-500">In Progress</span>
                                        </div>
                                        <form action="{{ route('lessons.sub.start', $subLesson) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="bg-[#006B87] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#004A68]">
                                                Continue
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($isLocked)
                    <!-- Locked Sub-Lesson -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden opacity-70">
                        <div class="flex flex-col sm:flex-row">
                            <div class="bg-gray-300 sm:w-2 w-full h-2 sm:h-auto"></div>
                            <div class="p-4 sm:p-6 flex-grow">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                                <i class="{{ $subLesson->icon }} text-gray-500"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold">{{ $subLesson->title }}</h3>
                                                <p class="text-sm text-gray-500">{{ $subLesson->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="flex items-center mr-4">
                                            <i class="fas fa-lock text-gray-500 mr-1"></i>
                                            <span class="text-sm text-gray-500">Locked</span>
                                        </div>
                                        <button disabled
                                            class="bg-gray-200 text-gray-400 px-4 py-2 rounded-lg font-medium cursor-not-allowed">
                                            Start
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Available Sub-Lesson -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="flex flex-col sm:flex-row">
                            <div class="bg-gray-300 sm:w-2 w-full h-2 sm:h-auto"></div>
                            <div class="p-4 sm:p-6 flex-grow">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                                <i class="{{ $subLesson->icon }} text-gray-700"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold">{{ $subLesson->title }}</h3>
                                                <p class="text-sm text-gray-500">{{ $subLesson->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="flex items-center mr-4">
                                            <i class="fas fa-circle text-gray-300 mr-1"></i>
                                            <span class="text-sm text-gray-500">Not Started</span>
                                        </div>
                                        <form action="{{ route('lessons.sub.start', $subLesson) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="bg-[#18AEB5] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#006B87]">
                                                Start
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Practice Button -->
        <div class="mt-8 flex justify-center">
            <a href=""
                class="bg-[#18AEB5] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#006B87] flex items-center">
                <i class="fas fa-dumbbell mr-2"></i>
                Practice All {{ $lesson->title }}
            </a>
        </div>
    </div>
@endsection
