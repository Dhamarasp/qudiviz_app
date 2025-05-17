@extends('app')

@section('title', 'Customize Profile')

@section('content')
    <!-- Customize Profile Header -->
    <div class="bg-[#18AEB5] text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Customize Profile</h1>
                    <p class="text-[#99E5E0]">Personalize your profile appearance</p>
                </div>
                <a href="{{ route('profile.index') }}" class="bg-white text-[#18AEB5] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                    Back to Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Customize Profile Content -->
    <div class="max-w-3xl mx-auto px-4 py-8">
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Cover Image -->
        {{-- <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Cover Image</h2>
            <form action="{{ route('profile.update-customization') }}" method="POST" enctype="multipart/form-data" class="flex flex-col">
                @csrf
                <div class="relative mb-4 w-full h-40 bg-gray-100 rounded-lg overflow-hidden">
                    @if($user->cover_image)
                        <img src="{{ asset('storage/cover_images/' . $user->cover_image) }}" alt="Cover Image" class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full">
                            <p class="text-gray-400">No cover image set</p>
                        </div>
                    @endif
                    <label for="cover_image" class="absolute bottom-2 right-2 bg-[#18AEB5] rounded-full p-2 text-white cursor-pointer">
                        <i class="fas fa-camera"></i>
                        <input type="file" id="cover_image" name="cover_image" class="hidden" accept="image/*">
                    </label>
                </div>
                <div class="inline-flex gap-2">
                    <button type="submit" class="bg-[#18AEB5] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#006B87]">
                        Save Cover Image
                    </button>
                    @if($user->cover_image)
                        <button type="button" onclick="document.getElementById('remove-cover-form').submit();" class="bg-red-200 text-red-700 px-4 py-2 rounded-lg font-medium hover:bg-red-300">
                            Remove
                        </button>
                    @endif
                </div>
            </form>
            
            <form id="remove-cover-form" action="{{ route('profile.remove-cover-image') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div> --}}

        <!-- Theme Color -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Theme Color</h2>
            <form action="{{ route('profile.update-customization') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-4 gap-4">
                    @foreach($themeColors as $color => $name)
                        <div>
                            <label class="flex flex-col items-center cursor-pointer">
                                <div class="w-12 h-12 rounded-full mb-2" style="background-color: {{ $color }}"></div>
                                <div class="flex items-center">
                                    <input type="radio" name="theme_color" value="{{ $color }}" class="mr-2" {{ $user->theme_color === $color ? 'checked' : '' }}>
                                    <span class="text-sm">{{ $name }}</span>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                <button type="submit" class="bg-[#18AEB5] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#006B87]">
                    Save Theme Color
                </button>
            </form>
        </div>

        <!-- Status Message -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Status Message</h2>
            <form action="{{ route('profile.update-customization') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="status_message" class="block text-sm font-medium text-gray-700 mb-1">Your Status</label>
                    <input type="text" id="status_message" name="status_message" value="{{ old('status_message', $user->status_message) }}"
                        placeholder="What's on your mind?" maxlength="100"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#18AEB5] focus:border-[#18AEB5]">
                    <p class="text-xs text-gray-500 mt-1">This will be displayed on your profile (max 100 characters)</p>
                </div>
                <!-- Add a hidden input with default theme color if user's theme is null -->
                @if(is_null($user->theme_color))
                    <input type="hidden" name="theme_color" value="#18AEB5">
                @endif
                <button type="submit" class="bg-[#18AEB5] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#006B87]">
                    Save Status
                </button>
            </form>
        </div>

        <!-- Privacy Settings -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Privacy Settings</h2>
            <form action="{{ route('profile.update-customization') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="show_xp_in_profile" name="show_xp_in_profile" class="mr-2" {{ $user->show_xp_in_profile ? 'checked' : '' }}>
                        <label for="show_xp_in_profile" class="text-sm font-medium text-gray-700">Show XP points on my profile</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="show_streak_in_profile" name="show_streak_in_profile" class="mr-2" {{ $user->show_streak_in_profile ? 'checked' : '' }}>
                        <label for="show_streak_in_profile" class="text-sm font-medium text-gray-700">Show streak days on my profile</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="show_level_in_profile" name="show_level_in_profile" class="mr-2" {{ $user->show_level_in_profile ? 'checked' : '' }}>
                        <label for="show_level_in_profile" class="text-sm font-medium text-gray-700">Show language level on my profile</label>
                    </div>
                </div>
                <!-- Add a hidden input with default theme color if user's theme is null -->
                @if(is_null($user->theme_color))
                    <input type="hidden" name="theme_color" value="#18AEB5">
                @endif
                <button type="submit" class="bg-[#18AEB5] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#006B87]">
                    Save Privacy Settings
                </button>
            </form>
        </div>
    </div>
    
    <script>
        // Preview cover image before upload
        document.getElementById('cover_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const coverContainer = document.querySelector('.h-40.bg-gray-100');
                    
                    // Remove text content if any
                    coverContainer.innerHTML = '';
                    
                    // Create image element
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';
                    img.alt = 'Cover Preview';
                    
                    // Add image to container
                    coverContainer.appendChild(img);
                    
                    // Re-add the label
                    const label = document.createElement('label');
                    label.setAttribute('for', 'cover_image');
                    label.className = 'absolute bottom-2 right-2 bg-[#18AEB5] rounded-full p-2 text-white cursor-pointer';
                    label.innerHTML = '<i class="fas fa-camera"></i><input type="file" id="cover_image" name="cover_image" class="hidden" accept="image/*">';
                    
                    coverContainer.appendChild(label);
                    
                    // Re-attach event listener to the new input
                    document.getElementById('cover_image').addEventListener('change', arguments.callee);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
