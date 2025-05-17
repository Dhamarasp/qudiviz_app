@extends('app')
@section('title', 'Leaderboard')

@section('content')
    <!-- Leaderboard Header -->
    <div class="bg-[#18AEB5] text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Leaderboard</h1>
                    <p class="text-green-100">Compete with friends and learners worldwide</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaderboard Content -->
    <div class="max-w-3xl mx-auto px-4 py-8">
        <!-- Your Position -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md mb-6">
            <div class="flex items-center">
                <span class="text-xl font-bold w-10 text-center">{{ $currentUserRank }}</span>
                <div class="h-12 w-12 rounded-full bg-purple-500 flex items-center justify-center mx-4 overflow-hidden">
                    @if($currentUser->profile_image)
                        <img src="{{ asset('storage/profile_images/' . $currentUser->profile_image) }}" alt="{{ $currentUser->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-white font-bold">{{ $currentUser->initials }}</span>
                    @endif
                </div>
                <div class="flex-grow">
                    <p class="font-bold">{{ $currentUser->display_name }} (You)</p>
                    <div class="flex items-center">
                        {{-- <i class="fas fa-fire text-orange-500 mr-1"></i>
                        <span class="text-sm text-gray-600">{{ $currentUser->streak_days }} day streak</span> --}}
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold">{{ number_format($currentUser->xp_points) }} <i class="fas fa-trophy text-yellow-500"></i></p>
                    {{-- <p class="text-sm text-gray-500">+{{ $currentUser->today_xp }} today</p> --}}
                </div>
            </div>
        </div>

        <!-- Leaderboard List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-4 bg-gray-50 border-b">
                <h2 class="font-bold">All Time Leaderboard</h2>
            </div>

            <ul class="divide-y divide-gray-200">
                <!-- Top 3 with medals -->
                @foreach($topUsers->take(3) as $index => $user)
                    <li class="p-4 hover:bg-gray-50 {{ $user->id === $currentUser->id ? 'bg-yellow-50' : '' }}">
                        <div class="flex items-center">
                            <span class="text-xl font-bold w-10 text-center">
                                @if($index === 0)
                                    <i class="fas fa-medal text-yellow-500"></i>
                                @elseif($index === 1)
                                    <i class="fas fa-medal text-gray-400"></i>
                                @elseif($index === 2)
                                    <i class="fas fa-medal text-yellow-700"></i>
                                @endif
                            </span>
                            <div class="h-12 w-12 rounded-full 
                                @if($index === 0) bg-red-500
                                @elseif($index === 1) bg-blue-500
                                @elseif($index === 2) bg-green-500
                                @else bg-gray-500
                                @endif
                                flex items-center justify-center mx-4 overflow-hidden">
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-white font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <p class="font-bold">{{ $user->display_name }}</p>
                                <div class="flex items-center">
                                    {{-- <i class="fas fa-fire text-orange-500 mr-1"></i>
                                    <span class="text-sm text-gray-600">{{ $user->streak_days }} day streak</span> --}}
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold">{{ number_format($user->xp_points) }} <i class="fas fa-trophy text-yellow-500"></i></p>
                                {{-- <p class="text-sm text-gray-500">+{{ $user->today_xp }} today</p> --}}
                            </div>
                        </div>
                    </li>
                @endforeach

                <!-- Regular positions -->
                @foreach($topUsers->slice(3) as $index => $user)
                    <li class="p-4 hover:bg-gray-50 {{ $user->id === $currentUser->id ? 'bg-yellow-50' : '' }}">
                        <div class="flex items-center">
                            <span class="text-xl font-bold w-10 text-center">{{ $index + 4 }}</span>
                            <div class="h-12 w-12 rounded-full 
                                @if($index % 5 === 0) bg-pink-500
                                @elseif($index % 5 === 1) bg-yellow-500
                                @elseif($index % 5 === 2) bg-indigo-500
                                @elseif($index % 5 === 3) bg-teal-500
                                @else bg-orange-500
                                @endif
                                flex items-center justify-center mx-4 overflow-hidden">
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-white font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <p class="font-bold">{{ $user->display_name }}</p>
                                <div class="flex items-center">
                                    <i class="fas fa-fire text-orange-500 mr-1"></i>
                                    <span class="text-sm text-gray-600">{{ $user->streak_days }} day streak</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold">{{ number_format($user->xp_points) }} <i class="fas fa-trophy text-yellow-500"></i></p>
                                <p class="text-sm text-gray-500">+{{ $user->today_xp }} today</p>
                            </div>
                        </div>
                    </li>
                @endforeach

                <!-- Nearby users (if current user is not in top 10) -->
                @if(!$currentUserInTop && $nearbyUsers->count() > 0)
                    <li class="p-4 bg-gray-100">
                        <div class="text-center text-gray-500">
                            <i class="fas fa-ellipsis-h"></i>
                        </div>
                    </li>
                    
                    @foreach($nearbyUsers as $index => $user)
                        <li class="p-4 hover:bg-gray-50 {{ $user->id === $currentUser->id ? 'bg-yellow-50' : '' }}">
                            <div class="flex items-center">
                                <span class="text-xl font-bold w-10 text-center">
                                    @if($user->id === $currentUser->id)
                                        {{ $currentUserRank }}
                                    @else
                                        @if($user->xp_points > $currentUser->xp_points)
                                            {{ $currentUserRank - ($nearbyUsers->count() - $index) }}
                                        @else
                                            {{ $currentUserRank + $index }}
                                        @endif
                                    @endif
                                </span>
                                <div class="h-12 w-12 rounded-full 
                                    @if($user->id === $currentUser->id) bg-purple-500
                                    @elseif($index % 3 === 0) bg-pink-500
                                    @elseif($index % 3 === 1) bg-yellow-500
                                    @else bg-indigo-500
                                    @endif
                                    flex items-center justify-center mx-4 overflow-hidden">
                                    @if($user->profile_image)
                                        <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-white font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <p class="font-bold">{{ $user->id === $currentUser->id ? 'You' : $user->display_name }}</p>
                                    <div class="flex items-center">
                                        <i class="fas fa-fire text-orange-500 mr-1"></i>
                                        <span class="text-sm text-gray-600">{{ $user->streak_days }} day streak</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold">{{ number_format($user->xp_points) }} <i class="fas fa-trophy text-yellow-500"></i></p>
                                    <p class="text-sm text-gray-500">+{{ $user->today_xp }} today</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endif
            </ul>

            <div class="p-4 border-t text-center">
                <button class="text-[#18AEB5] hover:text-[#006B87] font-medium">
                    Load More Users
                </button>
            </div>
        </div>

        <!-- Invite Friends -->
        <div class="mt-8 bg-white rounded-xl shadow-md p-6 text-center">
            <h3 class="text-lg font-bold mb-2">Invite Friends to Compete</h3>
            <p class="text-gray-600 mb-4">Challenge your friends and learn together!</p>
            <button class="bg-[#18AEB5] text-white px-6 py-2 rounded-lg font-bold hover:hover:bg-[#006B87]">
                <i class="fas fa-user-plus mr-2"></i> Invite Friends
            </button>
        </div>
    </div>
@endsection
