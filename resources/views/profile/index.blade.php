@extends('app')

@section('title', 'Profile')

@section('content')
    <!-- Profile Header -->
    <div class="bg-[{{ $user->theme_color }}] text-white py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($user->cover_image)
                <div class="w-full h-40 rounded-xl mb-6 overflow-hidden">
                    <img src="{{ asset('storage/cover_images/' . $user->cover_image) }}" alt="Cover Image" class="w-full h-full object-cover">
                </div>
            @endif
            
            <div class="flex flex-col md:flex-row items-center">
                <div class="h-24 w-24 rounded-full bg-[#004A68] flex items-center justify-center mb-4 md:mb-0 md:mr-6 overflow-hidden">
                    @if($user->profile_image)
                        <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-white font-bold text-4xl">{{ $user->initials }}</span>
                    @endif
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-3xl font-bold">{{ $user->display_name }}</h1>
                    <p class="text-[#99E5E0]">Joined {{ $user->created_at->format('F Y') }}</p>
                    @if($user->status_message)
                        <p class="mt-1 italic">"{{ $user->status_message }}"</p>
                    @endif
                    <div class="flex flex-wrap justify-center md:justify-start mt-2">
                        @if($language)
                            <span class="bg-[#004A68] px-3 py-1 rounded-full text-sm">{{ $language->name }} - {{ $user->level }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2 mt-4 md:mt-0 md:ml-auto">
                    <a href="{{ route('profile.customize') }}"
                        class="bg-white text-[{{ $user->theme_color }}] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                        Customize
                    </a>
                    <a href="{{ route('profile.edit') }}"
                        class="bg-white text-[{{ $user->theme_color }}] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                        Edit Profile
                    </a>
                    <a href="{{ url()->previous() }}"
                        class="bg-white text-[{{ $user->theme_color }}] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Stats Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Stats</h2>

                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium">Current Streak</span>
                            <span class="text-sm font-medium text-[{{ $user->theme_color }}]">{{ $currentStreak }} days</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-[{{ $user->theme_color }}] h-2 rounded-full" style="width: {{ min(($currentStreak / 30) * 100, 100) }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium">Longest Streak</span>
                            <span class="text-sm font-medium text-[{{ $user->theme_color }}]">{{ $longestStreak }} days</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-[{{ $user->theme_color }}] h-2 rounded-full" style="width: {{ min(($longestStreak / 30) * 100, 100) }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium">XP Earned</span>
                            <span class="text-sm font-medium text-[{{ $user->theme_color }}]">{{ number_format($user->xp_points) }} XP</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-[{{ $user->theme_color }}] h-2 rounded-full" style="width: {{ min(($user->xp_points / 2000) * 100, 100) }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium">Lessons Completed</span>
                            <span class="text-sm font-medium text-[{{ $user->theme_color }}]">{{ $lessonsCompleted }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-[{{ $user->theme_color }}] h-2 rounded-full" style="width: {{ min(($lessonsCompleted / 50) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievements Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Achievements</h2>

                <div class="grid grid-cols-3 gap-4">
                    @foreach($achievements as $achievement)
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 {{ $achievement['unlocked'] ? 'bg-[#99E5E0]' : 'bg-gray-200' }} rounded-full flex items-center justify-center mb-2">
                                <i class="{{ $achievement['icon'] }} {{ $achievement['unlocked'] ? 'text-[#004A68]' : 'text-gray-400' }}"></i>
                            </div>
                            <span class="text-xs text-center {{ $achievement['unlocked'] ? '' : 'text-gray-500' }}">{{ $achievement['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Friends Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Friends</h2>
                    <a href="{{ route('friends.index') }}" class="text-[{{ $user->theme_color }}] hover:text-primary-light">
                        <i class="fas fa-user-friends"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($friends as $friend)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-[{{ $user->theme_color }}] flex items-center justify-center mr-3 overflow-hidden">
                                    @if($friend->profile_image)
                                        <img src="{{ asset('storage/profile_images/' . $friend->profile_image) }}" alt="{{ $friend->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-white font-bold">{{ $friend->initials }}</span>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('profile.show', $friend) }}" class="font-medium hover:text-[{{ $user->theme_color }}]">
                                        {{ $friend->display_name }}
                                    </a>
                                    <p class="text-xs text-gray-500">{{ number_format($friend->xp_points) }} XP</p>
                                </div>
                            </div>
                            <span class="text-xs bg-[#99E5E0] text-[#004A68] px-2 py-1 rounded-full">
                                {{ $friend->streak_days }} day streak
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-gray-500 mb-2">You don't have any friends yet.</p>
                            <a href="{{ route('friends.search') }}" class="text-[{{ $user->theme_color }}] hover:underline">
                                Find friends to connect with
                            </a>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('friends.index') }}" class="block w-full mt-4 text-center text-[{{ $user->theme_color }}] hover:text-primary-light text-sm">
                    View All Friends
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Recent Activity</h2>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="space-y-6">
                    @forelse($recentActivity as $activity)
                        <div class="flex items-start">
                            <div class="h-10 w-10 rounded-full bg-[#99E5E0] flex items-center justify-center mr-4">
                                @if($activity['type'] === 'lesson' && $activity['status'] === 'completed')
                                    <i class="fas fa-star text-[#004A68]"></i>
                                @elseif($activity['type'] === 'sub_lesson' && $activity['status'] === 'completed')
                                    <i class="fas fa-check text-[#004A68]"></i>
                                @elseif($activity['status'] === 'in_progress')
                                    <i class="fas fa-spinner text-[#004A68]"></i>
                                @else
                                    <i class="fas fa-book text-[#004A68]"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium">
                                    @if($activity['status'] === 'completed')
                                        Completed "{{ $activity['title'] }}" {{ $activity['type'] === 'lesson' ? 'lesson' : 'sub-lesson' }}
                                    @elseif($activity['status'] === 'in_progress')
                                        Started "{{ $activity['title'] }}" {{ $activity['type'] === 'lesson' ? 'lesson' : 'sub-lesson' }}
                                    @else
                                        Interacted with "{{ $activity['title'] }}" {{ $activity['type'] === 'lesson' ? 'lesson' : 'sub-lesson' }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500">
                                    @if($activity['status'] === 'completed')
                                        Earned {{ $activity['xp_earned'] }} XP • 
                                    @endif
                                    {{ $activity['date']->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-gray-500">No recent activity found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
