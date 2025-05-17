@extends('app')

@section('title', $user->display_name . '\'s Profile')

@section('content')
    <!-- Profile Header -->
    <div class="bg-[{{ $user->theme_color }}] text-white py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($user->cover_image)
                <div class="w-full h-40 rounded-xl mb-6 overflow-hidden">
                    <img src="{{ asset('storage/cover_images/' . $user->cover_image) }}" alt="Cover Image"
                        class="w-full h-full object-cover">
                </div>
            @endif

            <div class="flex flex-col md:flex-row items-center">
                <div
                    class="h-24 w-24 rounded-full bg-[#004A68] flex items-center justify-center mb-4 md:mb-0 md:mr-6 overflow-hidden">
                    @if ($user->profile_image)
                        <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="{{ $user->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-white font-bold text-4xl">{{ $user->initials }}</span>
                    @endif
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-3xl font-bold">{{ $user->display_name }}</h1>
                    <p class="text-[#99E5E0]">Joined {{ $user->created_at->format('F Y') }}</p>
                    @if ($user->status_message)
                        <p class="mt-1 italic">"{{ $user->status_message }}"</p>
                    @endif
                    <div class="flex flex-wrap justify-center md:justify-start mt-2">
                        @if ($language && $user->show_level_in_profile)
                            <span class="bg-[#004A68] px-3 py-1 rounded-full text-sm">{{ $language->name }} -
                                {{ $user->level }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2 mt-4 md:mt-0 md:ml-auto">
                    @if (Auth::id() !== $user->id)
                        @if ($friendshipStatus === 'accepted')
                            <form action="{{ route('friends.remove-friend', $user) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to remove this friend?');">
                                @csrf
                                <button type="submit"
                                    class="bg-red-400 text-[{{ $user->theme_color }}] px-4 py-2 rounded-lg font-bold hover:bg-red-600 text-gray-700">
                                    Remove Friend
                                </button>
                            </form>
                        @elseif($friendshipStatus === 'pending' && Auth::user()->hasSentFriendRequestTo($user))
                            <form action="{{ route('friends.cancel-request', $user) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-white text-[{{ $user->theme_color }}] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                                    <i class="fas fa-clock mr-1"></i> Cancel Request
                                </button>
                            </form>
                        @elseif($friendshipStatus === 'pending' && Auth::user()->hasFriendRequestFrom($user))
                            <form action="{{ route('friends.accept-request', $user) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-white text-[{{ $user->theme_color }}] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                                    <i class="fas fa-check mr-1"></i> Accept Request
                                </button>
                            </form>
                            <form action="{{ route('friends.reject-request', $user) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-300">
                                    <i class="fas fa-times mr-1"></i> Decline
                                </button>
                            </form>
                        @elseif($friendshipStatus === 'blocked')
                            <form action="{{ route('friends.unblock-user', $user) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-white text-[{{ $user->theme_color }}] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                                    <i class="fas fa-ban mr-1"></i> Unblock User
                                </button>
                            </form>
                        @else
                            <form action="{{ route('friends.send-request', $user) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-white text-[{{ $user->theme_color }}] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                                    <i class="fas fa-user-plus mr-1"></i> Add Friend
                                </button>
                            </form>
                            <div class="relative group">
                                <button type="button"
                                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-300">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
                                    <form action="{{ route('friends.block-user', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 w-full text-left">
                                            <i class="fas fa-ban mr-1"></i> Block User
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endif
                    <a href="{{ route('profile.index') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-300">
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
                    @if ($user->show_streak_in_profile)
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium">Current Streak</span>
                                <span class="text-sm font-medium text-[{{ $user->theme_color }}]">{{ $currentStreak }}
                                    days</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-[{{ $user->theme_color }}] h-2 rounded-full"
                                    style="width: {{ min(($currentStreak / 30) * 100, 100) }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium">Longest Streak</span>
                                <span class="text-sm font-medium text-[{{ $user->theme_color }}]">{{ $longestStreak }}
                                    days</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-[{{ $user->theme_color }}] h-2 rounded-full"
                                    style="width: {{ min(($longestStreak / 30) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    @endif

                    @if ($user->show_xp_in_profile)
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium">XP Earned</span>
                                <span
                                    class="text-sm font-medium text-[{{ $user->theme_color }}]">{{ number_format($user->xp_points) }}
                                    XP</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-[{{ $user->theme_color }}] h-2 rounded-full"
                                    style="width: {{ min(($user->xp_points / 2000) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium">Lessons Completed</span>
                            <span
                                class="text-sm font-medium text-[{{ $user->theme_color }}]">{{ $lessonsCompleted }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-[{{ $user->theme_color }}] h-2 rounded-full"
                                style="width: {{ min(($lessonsCompleted / 50) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievements Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Achievements</h2>

                <div class="grid grid-cols-3 gap-4">
                    @foreach ($achievements as $achievement)
                        <div class="flex flex-col items-center">
                            <div
                                class="w-12 h-12 {{ $achievement['unlocked'] ? 'bg-[#99E5E0]' : 'bg-gray-200' }} rounded-full flex items-center justify-center mb-2">
                                <i
                                    class="{{ $achievement['icon'] }} {{ $achievement['unlocked'] ? 'text-[#004A68]' : 'text-gray-400' }}"></i>
                            </div>
                            <span
                                class="text-xs text-center {{ $achievement['unlocked'] ? '' : 'text-gray-500' }}">{{ $achievement['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Bio Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">About</h2>

                @if ($user->bio)
                    <p class="text-gray-700">{{ $user->bio }}</p>
                @else
                    <p class="text-gray-500 italic">No bio provided.</p>
                @endif

                <div class="mt-6">
                    <h3 class="font-medium text-gray-700 mb-2">Learning</h3>
                    @if ($language)
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-[{{ $user->theme_color }}] flex items-center justify-center mr-2">
                                <i class="fas fa-language text-white"></i>
                            </div>
                            <span>{{ $language->name }}</span>
                        </div>
                    @else
                        <p class="text-gray-500 italic">No language selected.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
