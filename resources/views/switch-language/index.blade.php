@extends('app')

@section('title', 'Switch Language')

@section('content')
    <!-- Switch Language Header -->
    <div class="bg-[#18AEB5] text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Switch Language</h1>
                    <p class="text-[#99E5E0]">Choose a new language to learn</p>
                </div>
                <a href="{{ route('lessons') }}" class="bg-white text-[#18AEB5] px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                    Cancel
                </a>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="max-w-5xl mx-auto px-4 py-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" id="language-search" placeholder="Search languages..."
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-[#18AEB5] focus:border-transparent" />
        </div>
    </div>

    @if(isset($currentLanguage))
    <!-- Current Language -->
    <div class="max-w-5xl mx-auto px-4 mb-6">
        <div class="bg-white rounded-xl shadow-md p-4">
            <h2 class="text-lg font-bold mb-4">Currently Learning</h2>
            <div class="flex items-center">
                <img src="{{ $currentLanguage->flag_url }}"
                    alt="{{ $currentLanguage->name }} Flag" class="w-12 h-12 rounded-full border-2 border-[#18AEB5]" />
                <div class="ml-4">
                    <h3 class="font-bold">{{ $currentLanguage->name }}</h3>
                    <p class="text-sm text-gray-500">
                        {{ Auth::user()->level ?? 'Beginner' }}
                        @if(isset($completedUnits))
                        · {{ $completedUnits }} units completed
                        @endif
                    </p>
                </div>
                <div class="ml-auto">
                    <span class="bg-[#99E5E0] text-[#004A68] px-3 py-1 rounded-full text-sm font-medium">Active</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Language Grid -->
    <div class="max-w-5xl mx-auto px-4 py-8">
        <h2 class="text-xl font-bold mb-4">All Languages</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="language-grid">
            <!-- Language Cards -->
            @foreach($languages as $language)
            <div class="language-card" data-name="{{ strtolower($language->name) }}">
                <form action="{{ route('switch-language.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="language_id" value="{{ $language->id }}">
                    <button type="submit" class="w-full text-left bg-white rounded-xl shadow-md p-4 flex items-center hover:shadow-lg transition">
                        <img src="{{ $language->flag_url }}"
                            alt="{{ $language->name }} Flag" class="w-12 h-12 rounded-full" />
                        <div class="ml-4">
                            <h3 class="font-bold">{{ $language->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $language->description }}</p>
                        </div>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('language-search');
            const languageCards = document.querySelectorAll('.language-card');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                languageCards.forEach(card => {
                    const languageName = card.dataset.name;
                    if (languageName.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
