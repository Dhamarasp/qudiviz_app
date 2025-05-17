@extends('app')

@section('title', 'Find Friends')

@section('content')
    <!-- Search Header -->
    <div class="bg-[#18AEB5] text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Find Friends</h1>
                    <p class="text-[#99E5E0]">Connect with other language learners</p>
                </div>
                <a href="{{ route('friends.index') }}" class="bg-white text-[#18AEB5] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                    Back to Friends
                </a>
            </div>
        </div>
    </div>

    <!-- Search Content -->
    <div class="max-w-5xl mx-auto px-4 py-8">
        <!-- Search Form -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <form action="{{ route('friends.search') }}" method="GET" class="flex gap-2">
                <input type="text" name="query" value="{{ $query ?? '' }}" placeholder="Search by name or username" 
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#18AEB5]">
                <button type="submit" class="bg-[#18AEB5] text-white px-4 py-2 rounded-lg hover:bg-[#006B87]">
                    <i class="fas fa-search mr-1"></i> Search
                </button>
            </form>
        </div>

        <!-- Search Results -->
        @if(isset($users))
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Search Results</h2>
                
                @if($users->count() > 0)
                    <div class="space-y-4">
                        @foreach($users as $user)
                            <div class="flex items-center justify-between border-b pb-4">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-full bg-[#18AEB5] flex items-center justify-center mr-4 overflow-hidden">
                                        @if($user->profile_image)
                                            <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-white font-bold text-xl">{{ $user->initials }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('profile.show', $user) }}" class="font-medium hover:text-[#18AEB5]">
                                            {{ $user->display_name }}
                                        </a>
                                        <p class="text-xs text-gray-500">{{ $user->xp_points }} XP • {{ $user->streak_days }} day streak</p>
                                    </div>
                                </div>
                                <div>
                                    @if($user->friendship_status === 'accepted')
                                        <span class="text-green-500 text-sm">
                                            <i class="fas fa-check-circle mr-1"></i> Friends
                                        </span>
                                    @elseif($user->friendship_status === 'pending' && Auth::user()->hasSentFriendRequestTo($user))
                                        <form action="{{ route('friends.cancel-request', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-gray-500 text-sm hover:text-red-500">
                                                <i class="fas fa-clock mr-1"></i> Request Sent
                                            </button>
                                        </form>
                                    @elseif($user->friendship_status === 'pending' && Auth::user()->hasFriendRequestFrom($user))
                                        <div class="flex gap-2">
                                            <form action="{{ route('friends.accept-request', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-[#18AEB5] text-white px-3 py-1 rounded-lg text-sm hover:bg-[#006B87]">
                                                    Accept
                                                </button>
                                            </form>
                                            <form action="{{ route('friends.reject-request', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-gray-200 text-gray-700 px-3 py-1 rounded-lg text-sm hover:bg-gray-300">
                                                    Decline
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($user->friendship_status === 'blocked')
                                        <form action="{{ route('friends.unblock-user', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-red-500 text-sm hover:text-red-700">
                                                <i class="fas fa-ban mr-1"></i> Unblock
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('friends.send-request', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-[#18AEB5] text-white px-3 py-1 rounded-lg text-sm hover:bg-[#006B87]">
                                                <i class="fas fa-user-plus mr-1"></i> Add Friend
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-search text-5xl"></i>
                        </div>
                        <p class="text-gray-500">No users found matching "{{ $query }}".</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
