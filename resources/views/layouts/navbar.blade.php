<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
        <div class="flex items-center">
            <a href="{{ route('home') }}">
                <div class="flex-shrink-0 flex items-center">
                    <img class="h-10 w-10" src="{{ asset('images/qudiviz_logo.png') }}" alt="Qudiviz Logo">
                    <span class="ml-1 text-xl font-bold text-[#18AEB5]">Qudiviz</span>
                </div>
            </a>

            @if (request()->is('lessons') ||
                    request()->is('practice') ||
                    request()->is('leaderboard') ||
                    request()->is('lessons/sub') ||
                    request()->is('lessons/switch-language') ||
                    request()->is('profile'))
                <!-- Desktop Navigation -->
                <div class="hidden md:ml-6 md:flex md:space-x-4">
                    <a href="{{ route('lessons') }}"
                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#18AEB5] hover:bg-gray-100">
                        Lessons
                    </a>
                    <a href="{{ route('practice.index') }}"
                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#18AEB5] hover:bg-gray-100">
                        Practice
                    </a>
                    <a href="{{ route('leaderboard') }}"
                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-[#18AEB5] hover:bg-gray-100">
                        Leaderboard
                    </a>
                </div>
            @endif
        </div>

        @if (request()->is('/'))
            <div class="flex items-center space-x-2">
                @auth
                    <div class="flex items-center bg-gray-100 rounded-full px-3 py-1">
                        <a href="{{ route('lessons') }}" class="ml-1 font-bold">My Lessons</a>
                    </div>
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center bg-gray-100 rounded-full px-3 py-1">
                            <span class="ml-1 font-bold">Logout</span>
                        </button>
                    </form>
                @else
                    <div class="flex items-center bg-gray-100 rounded-full px-3 py-1">
                        <a href="{{ route('login') }}" class="ml-1 font-bold">Login</a>
                    </div>
                    <div class="flex items-center bg-gray-100 rounded-full px-3 py-1">
                        <a href="{{ route('register.index') }}" class="ml-1 font-bold">Register</a>
                    </div>
                @endauth
            </div>
        @endif

        @if (request()->is('login') || request()->is('register'))
            <div class="flex items-center space-x-2">
                <div class="flex items-center bg-gray-100 rounded-full px-3 py-1">
                    <a href="{{ route('home') }}" class="ml-1 font-bold">Back</a>
                </div>
            </div>
        @endif

        @if (request()->is('lessons') ||
                request()->is('practice') ||
                request()->is('leaderboard') ||
                request()->is('lessons/sub') ||
                request()->is('lessons/switch-language') ||
                request()->is('profile*'))
            <div class="hidden md:flex items-center space-x-3">
                {{-- <div class="hidden sm:flex items-center bg-gray-100 rounded-full px-3 py-1">
                    <i class="fas fa-trophy text-yellow-500"></i>
                    <span class="ml-1 font-bold">340</span>
                </div> --}}
                <a href="{{ route('profile.index') }}" class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-[#18AEB5] flex items-center justify-center">
                        <span class="text-white font-bold">{{ Auth::user()->name[0] }}</span>
                    </div>
                </a>
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center bg-gray-300 h-8 w-8 rounded-full">
                        <i class="fa-solid fa-right-from-bracket text-xl text-red-500"></i>
                    </button>
                </form>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button id="mobile-menu-button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-primary hover:bg-gray-100 focus:outline-none"
                    aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <i class="fas fa-bars text-xl" id="menu-icon"></i>
                </button>
            </div>
        @endif

    </div>
</div>

@include('layouts.sidebar')

<!-- JavaScript for Mobile Menu Toggle -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');

        menuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');

            // Toggle between hamburger and X icon
            if (menuIcon.classList.contains('fa-bars')) {
                menuIcon.classList.remove('fa-bars');
                menuIcon.classList.add('fa-times');
            } else {
                menuIcon.classList.remove('fa-times');
                menuIcon.classList.add('fa-bars');
            }
        });
    });
</script>
