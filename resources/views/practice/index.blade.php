@extends('app')
@section('title', 'Practice')

@section('content')
    <!-- Practice Header -->
    <div class="bg-[#18AEB5] text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Practice</h1>
                    <p class="text-[#99E5E0]">Strengthen your skills and maintain your streak</p>
                </div>
                <div class="items-center">
                    <div class="bg-white text-yellow-500 px-4 py-2 rounded-lg font-bold">
                        <i class="fas fa-trophy mr-2"><span class="ml-1 text-black">{{ Auth::user()->xp_points }}</span></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Practice Content -->
    <div class="max-w-5xl mx-auto px-4 py-8">
        <!-- Practice Types -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Daily Practice -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-[#99E5E0] p-4 flex items-center">
                    <div class="w-12 h-12 bg-[#18AEB5] rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-fire text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">Daily Practice</h2>
                        <p class="text-sm text-gray-600">Maintain your streak</p>
                    </div>
                </div>
                <div class="p-6">
                    <p class="mb-4">Complete a quick practice session to maintain your streak and review what you've
                        learned.</p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">5-10 minutes</span>
                        <form action="{{ route('practice.start') }}" method="POST">
                            @csrf
                            <input type="hidden" name="practice_type" value="daily">
                            <input type="hidden" name="num_questions" value="10">
                            <button type="submit" class="bg-[#18AEB5] text-white px-4 py-2 rounded-lg font-bold hover:bg-[#006B87]">
                                Start Quiz
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Weak Skills -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-[#99E5E0] p-4 flex items-center">
                    <div class="w-12 h-12 bg-[#004A68] rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-dumbbell text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">Weak Skills</h2>
                        <p class="text-sm text-gray-600">Strengthen areas you need help with</p>
                    </div>
                </div>
                <div class="p-6">
                    <p class="mb-4">Focus on the skills where you've made the most mistakes to improve your overall
                        proficiency.
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">10-15 minutes</span>
                        <form action="{{ route('practice.start') }}" method="POST">
                            @csrf
                            <input type="hidden" name="practice_type" value="weak_skills">
                            <input type="hidden" name="num_questions" value="15">
                            <button type="submit" class="bg-[#004A68] text-white px-4 py-2 rounded-lg font-bold hover:bg-[#006B87]">
                                Start Quiz
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Practice Categories -->
        <h2 class="text-xl font-bold mb-4">Practice by Category</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            @foreach($categories as $category)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                            <i class="{{ $category->icon }} text-gray-700"></i>
                        </div>
                        <h3 class="text-lg font-bold">{{ $category->name }}</h3>
                    </div>
                    <p class="text-gray-600 mb-4">{{ $category->description }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                            @if($category->slug === 'grammar')
                                8 rules
                            @elseif($category->slug === 'vocabulary')
                                120 words
                            @elseif($category->slug === 'reading')
                                12 texts
                            @elseif($category->slug === 'listening')
                                15 exercises
                            @elseif($category->slug === 'speaking')
                                10 exercises
                            @elseif($category->slug === 'writing')
                                8 exercises
                            @endif
                        </span>
                        <form action="{{ route('practice.start') }}" method="POST">
                            @csrf
                            <input type="hidden" name="practice_type" value="{{ $category->slug }}">
                            <input type="hidden" name="num_questions" value="10">
                            <button type="submit" class="text-[#18AEB5] hover:text-[#006B87] font-bold">
                                Practice <i class="fas fa-arrow-right ml-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Practice Stats -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Your Practice Stats</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Practice Sessions</h3>
                    <div class="flex items-end">
                        <span class="text-3xl font-bold mr-2">{{ $stats->total_practice_sessions }}</span>
                        <span class="text-sm text-green-500 mb-1">+3 this week</span>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Total Practice Time</h3>
                    <div class="flex items-end">
                        <span class="text-3xl font-bold mr-2">{{ $stats->total_practice_time_hours }}h</span>
                        <span class="text-sm text-green-500 mb-1">+45m this week</span>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Accuracy Rate</h3>
                    <div class="flex items-end">
                        <span class="text-3xl font-bold mr-2">{{ $stats->accuracy_percentage }}%</span>
                        <span class="text-sm text-green-500 mb-1">+2% this week</span>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Weekly Practice Activity</h3>
                <div class="grid grid-cols-7 gap-2">
                    @php
                        $weeklyData = $stats->weekly_practice_data ?? [0, 0, 0, 0, 0, 0, 0];
                        $maxValue = max($weeklyData) > 0 ? max($weeklyData) : 1;
                    @endphp
                    
                    @foreach($weeklyData as $index => $value)
                        @php
                            $height = ($value / $maxValue) * 100;
                            $dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                        @endphp
                        <div class="flex flex-col items-center">
                            <div class="w-full bg-orange-100 rounded-md h-24 relative">
                                <div class="absolute bottom-0 w-full bg-[#18AEB5] rounded-b-md" style="height: {{ $height }}%"></div>
                            </div>
                            <span class="text-xs mt-1">{{ $dayNames[$index] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
