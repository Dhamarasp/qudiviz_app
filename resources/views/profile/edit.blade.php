@extends('app')

@section('title', 'Edit Profile')

@section('content')
    <!-- Edit Profile Header -->
    <div class="bg-[#18AEB5] text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Edit Profile</h1>
                    <p class="text-[#99E5E0]">Update your personal information</p>
                </div>
                <a href="{{ route('profile.index') }}" class="bg-white text-[#18AEB5] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                    Cancel
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Profile Content -->
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

        <!-- Profile Picture -->
        {{-- <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Profile Picture</h2>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center">
                @csrf
                <div class="relative mb-4 sm:mb-0 sm:mr-6">
                    <div class="h-24 w-24 rounded-full bg-[#18AEB5] flex items-center justify-center overflow-hidden">
                        @if($user->profile_image)
                            <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-white font-bold text-4xl">{{ $user->initials }}</span>
                        @endif
                    </div>
                    <label for="profile_image" class="absolute bottom-0 right-0 bg-[#18AEB5] rounded-full p-2 text-white cursor-pointer">
                        <i class="fas fa-camera"></i>
                        <input type="file" id="profile_image" name="profile_image" class="hidden" accept="image/*">
                    </label>
                </div>
                <div class="inline-flex gap-2">
                    <button type="submit" class="bg-[#18AEB5] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#006B87]">
                        Save
                    </button>
                    @if($user->profile_image)
                        <button type="button" onclick="document.getElementById('remove-image-form').submit();" class="bg-red-200 text-red-700 px-4 py-2 rounded-lg font-medium hover:bg-red-300">
                            Remove
                        </button>
                    @endif
                </div>
            </form>
            
            <form id="remove-image-form" action="{{ route('profile.remove-profile-image') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div> --}}

        <!-- Personal Information -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Personal Information</h2>
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#18AEB5] focus:border-[#18AEB5]">
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#18AEB5] focus:border-[#18AEB5]">
                    <p class="text-xs text-gray-500 mt-1">This will be visible to other users</p>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#18AEB5] focus:border-[#18AEB5]">
                </div>

                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                    <textarea id="bio" name="bio" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#18AEB5] focus:border-[#18AEB5]">{{ old('bio', $user->bio) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Tell others a bit about yourself (max 150 characters)</p>
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="bg-[#18AEB5] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#006B87]">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Change Password -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Change Password</h2>
            <form action="{{ route('profile.update-password') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" id="current_password" name="current_password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#18AEB5] focus:border-[#18AEB5]">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" id="password" name="password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#18AEB5] focus:border-[#18AEB5]">
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#18AEB5] focus:border-[#18AEB5]">
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="bg-[#18AEB5] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#006B87]">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Actions -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Account Actions</h2>
            <div class="space-y-4">
                <button type="button" class="flex items-center text-red-500 hover:text-red-600" onclick="confirmDelete()">
                    <i class="fas fa-trash-alt mr-2"></i>
                    <span>Delete Account</span>
                </button>
            </div>
        </div>
    </div>
    
    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                // In a real app, you would submit a form to delete the account
                alert('Account deletion would be processed here.');
            }
        }
        
        // Preview profile image before upload
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const profilePic = document.querySelector('.h-24.w-24.rounded-full');
                    
                    // Remove text content if any
                    profilePic.innerHTML = '';
                    
                    // Create image element
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';
                    img.alt = 'Profile Preview';
                    
                    // Add image to container
                    profilePic.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
