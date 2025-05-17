@extends('app')
@section('title', 'Lesson')

@section('content')
    <!-- Language Header -->
    <div class="bg-[#004A68] text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ $language->flag_url }}"
                        alt="{{ $language->name }} Flag" class="w-12 h-12 rounded-full border-2 border-white">
                    <h1 class="ml-3 text-2xl font-bold">{{ $language->name }}</h1>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('switch-language') }}"
                        class="bg-white text-[#004A68] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                        Switch Language
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Skill Tree -->
    <div class="max-w-3xl mx-auto px-4 py-8">
        @foreach($units as $unit)
            <div class="mb-12">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Unit {{ $unit->order }}: {{ $unit->title }}</h2>
                    @php
                        $totalLessons = $unit->lessons->count();
                        $completedLessons = $unit->lessons->filter(function($lesson) use ($userProgress) {
                            return isset($userProgress[$lesson->id]) && $userProgress[$lesson->id]->status === 'completed';
                        })->count();
                    @endphp
                    <span class="text-sm text-gray-500">{{ $completedLessons }}/{{ $totalLessons }} completed</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($unit->lessons as $lesson)
                        @php
                            $status = isset($userProgress[$lesson->id]) ? $userProgress[$lesson->id]->status : 'not_started';
                            $isCompleted = $status === 'completed';
                            $isInProgress = $status === 'in_progress';
                            $isLocked = !$isCompleted && !$isInProgress && $lesson->order > 1 && 
                                        (!isset($userProgress[$unit->lessons[$lesson->order - 2]->id]) || 
                                         $userProgress[$unit->lessons[$lesson->order - 2]->id]->status !== 'completed');
                        @endphp

                        @if($isCompleted)   
                            <!-- Completed Lesson -->
                            <form action="{{ route('lessons.start', $lesson) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-white rounded-xl shadow-md p-4 flex flex-col items-center hover:shadow-lg transition border-2 border-[#18AEB5] text-left">
                                    <div class="w-16 h-16 bg-[#18AEB5] rounded-full flex items-center justify-center mb-3">
                                        <i class="fas fa-check text-white text-xl"></i>
                                    </div>
                                    <span class="font-bold">{{ $lesson->title }}</span>
                                    <span class="text-sm text-gray-500">Completed</span>
                                </button>
                            </form>
                        @elseif($isInProgress)
                            <!-- Current Lesson -->
                            <form action="{{ route('lessons.start', $lesson) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-white rounded-xl shadow-md p-4 flex flex-col items-center hover:shadow-lg transition border-2 border-[#006B87] text-left">
                                    <div class="w-16 h-16 bg-[#006B87] rounded-full flex items-center justify-center mb-3">
                                        <i class="fas fa-star text-white text-xl"></i>
                                    </div>
                                    <span class="font-bold">{{ $lesson->title }}</span>
                                    <span class="text-sm text-gray-500">In Progress</span>
                                </button>
                            </form>
                        @elseif($isLocked)
                            <!-- Locked Lesson -->
                            <div class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center hover:shadow-lg transition opacity-70">
                                <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center mb-3">
                                    <i class="fas fa-lock text-gray-500 text-xl"></i>
                                </div>
                                <span class="font-bold">{{ $lesson->title }}</span>
                                <span class="text-sm text-gray-500">Locked</span>
                            </div>
                        @else
                            <!-- Available Lesson -->
                            <form action="{{ route('lessons.start', $lesson) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-white rounded-xl shadow-md p-4 flex flex-col items-center hover:shadow-lg transition text-left">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-3">
                                        <i class="fas fa-book text-gray-700 text-xl"></i>
                                    </div>
                                    <span class="font-bold">{{ $lesson->title }}</span>
                                    <span class="text-sm text-gray-500">Start</span>
                                </button>
                            </form>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection