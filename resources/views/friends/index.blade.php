@extends('app')

@section('title', 'Friends')

@section('content')
    <!-- Friends Header -->
    <div class="bg-[#18AEB5] text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Friends</h1>
                    <p class="text-[#99E5E0]">Connect with other language learners</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('friends.search') }}"
                        class="bg-white text-[#18AEB5] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                        <i class="fas fa-search mr-1"></i> Find Friends
                    </a>
                    <a href="{{ route('profile.index') }}"
                        class="bg-white text-[#18AEB5] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Friends Content -->
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Friend Requests -->
            <div class="md:col-span-3">
                @if ($pendingRequests->count() > 0)
                    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                        <h2 class="text-xl font-bold mb-4">Friend Requests ({{ $pendingRequests->count() }})</h2>
                        <div class="space-y-4">
                            @foreach ($pendingRequests as $request)
                                <div class="flex items-center justify-between border-b pb-4">
                                    <div class="flex items-center">
                                        <div
                                            class="h-12 w-12 rounded-full bg-[#18AEB5] flex items-center justify-center mr-4 overflow-hidden">
                                            @if ($request->user->profile_image)
                                                <img src="{{ asset('storage/profile_images/' . $request->user->profile_image) }}"
                                                    alt="{{ $request->user->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span
                                                    class="text-white font-bold text-xl">{{ $request->user->initials }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('profile.show', $request->user) }}"
                                                class="font-medium hover:text-[#18AEB5]">
                                                {{ $request->user->display_name }}
                                            </a>
                                            <p class="text-xs text-gray-500">{{ $request->user->xp_points }} XP</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <form action="{{ route('friends.accept-request', $request->user) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="bg-[#18AEB5] text-white px-3 py-1 rounded-lg text-sm hover:bg-[#006B87]">
                                                Accept
                                            </button>
                                        </form>
                                        <form action="{{ route('friends.reject-request', $request->user) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="bg-gray-200 text-gray-700 px-3 py-1 rounded-lg text-sm hover:bg-gray-300">
                                                Decline
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Friends List -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold mb-4">Your Friends ({{ $friends->count() }})</h2>

                    @if ($friends->count() > 0)
                        <div class="space-y-4">
                            @foreach ($friends as $friend)
                                <div class="flex items-center justify-between border-b pb-4">
                                    <div class="flex items-center">
                                        <div
                                            class="h-12 w-12 rounded-full bg-[#18AEB5] flex items-center justify-center mr-4 overflow-hidden">
                                            @if ($friend->profile_image)
                                                <img src="{{ asset('storage/profile_images/' . $friend->profile_image) }}"
                                                    alt="{{ $friend->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-white font-bold text-xl">{{ $friend->initials }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('profile.show', $friend) }}"
                                                class="font-medium hover:text-[#18AEB5]">
                                                {{ $friend->display_name }}
                                            </a>
                                            <p class="text-xs text-gray-500">{{ $friend->xp_points }} XP •
                                                {{ $friend->streak_days }} day streak</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <form action="{{ route('friends.remove-friend', $friend) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to remove this friend?');">
                                            @csrf
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-user-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-user-friends text-5xl"></i>
                            </div>
                            <p class="text-gray-500 mb-4">You don't have any friends yet.</p>
                            <a href="{{ route('friends.search') }}"
                                class="bg-[#18AEB5] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#006B87]">
                                Find Friends
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pending Requests & Suggestions -->
            <div>
                @if ($sentRequests->count() > 0)
                    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                        <h2 class="text-lg font-bold mb-4">Pending Requests ({{ $sentRequests->count() }})</h2>
                        <div class="space-y-4">
                            @foreach ($sentRequests as $request)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div
                                            class="h-10 w-10 rounded-full bg-[#18AEB5] flex items-center justify-center mr-3 overflow-hidden">
                                            @if ($request->friend->profile_image)
                                                <img src="{{ asset('storage/profile_images/' . $request->friend->profile_image) }}"
                                                    alt="{{ $request->friend->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-white font-bold">{{ $request->friend->initials }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('profile.show', $request->friend) }}"
                                                class="font-medium hover:text-[#18AEB5]">
                                                {{ $request->friend->display_name }}
                                            </a>
                                        </div>
                                    </div>
                                    <form action="{{ route('friends.cancel-request', $request->friend) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-sm text-gray-500 hover:text-red-500">
                                            Cancel
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold mb-4">Quick Actions</h2>
                    <div class="space-y-2">
                        <a href="{{ route('friends.search') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-search text-blue-500"></i>
                            </div>
                            <span>Find Friends</span>
                        </a>
                        <a href="{{ route('friends.blocked') }}"
                            class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                <i class="fas fa-ban text-red-500"></i>
                            </div>
                            <span>Blocked Users</span>
                        </a>
                        <a href="{{ route('leaderboard') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                                <i class="fas fa-trophy text-yellow-500"></i>
                            </div>
                            <span>Leaderboard</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
