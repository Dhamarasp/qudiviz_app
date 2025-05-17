@extends('app')

@section('title', 'Blocked Users')

@section('content')
    <!-- Blocked Users Header -->
    <div class="bg-[#18AEB5] text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Blocked Users</h1>
                    <p class="text-[#99E5E0]">Manage users you've blocked</p>
                </div>
                <a href="{{ route('friends.index') }}" class="bg-white text-[#18AEB5] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                    Back to Friends
                </a>
            </div>
        </div>
    </div>

    <!-- Blocked Users Content -->
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Blocked Users ({{ $blockedUsers->count() }})</h2>
            
            @if($blockedUsers->count() > 0)
                <div class="space-y-4">
                    @foreach($blockedUsers as $user)
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
                                    <p class="font-medium">{{ $user->display_name }}</p>
                                    <p class="text-xs text-gray-500">Blocked user</p>
                                </div>
                            </div>
                            <form action="{{ route('friends.unblock-user', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-gray-200 text-gray-700 px-3 py-1 rounded-lg text-sm hover:bg-gray-300">
                                    Unblock
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-ban text-5xl"></i>
                    </div>
                    <p class="text-gray-500">You haven't blocked any users.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
