@if (request()->is('lessons') ||
        request()->is('practice') ||
        request()->is('leaderboard') ||
        request()->is('lessons/sub')||
        request()->is('lessons/switch-language')||
        request()->is('profile*'))
    <!-- Mobile menu, show/hide based on menu state -->
    <div class="hidden md:hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white shadow-lg">
            <a href="{{ route('lessons') }}"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-[#18AEB5] hover:bg-gray-100">
                <i class="fas fa-crown mr-2"></i>
                Lessons
            </a>
            <a href="{{ route('practice.index') }}"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-[#18AEB5] hover:bg-gray-100">
                <i class="fas fa-fire mr-2"></i>
                Practice
            </a>
            <a href="{{ route('leaderboard') }}"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-[#18AEB5] hover:bg-gray-100">
                <i class="fas fa-trophy mr-2"></i>
                Leaderboard
            </a>
            <a href="{{ route('profile.index') }}"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-[#18AEB5] hover:bg-gray-100">
                <i class="fas fa-user mr-2"></i>
                Profile
            </a>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-3">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-[#004A68] flex items-center justify-center">
                            <span class="text-white font-bold">{{ Auth::user()->name[0] }}</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                    </div>
                    {{-- <div class="ml-3 flex">
                        <div class="flex items-center bg-gray-100 rounded-full px-3 py-1">
                            <i class="fas fa-trophy text-yellow-500"></i>
                            <span class="ml-1 font-bold">340</span>
                        </div>
                    </div> --}}
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" 
                            class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-red-500 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif