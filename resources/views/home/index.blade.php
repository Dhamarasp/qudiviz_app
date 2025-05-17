@extends('app')
@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <div class="bg-[#18AEB5] text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">Learn a language for free. Forever.</h1>
                <p class="text-xl mb-8">Fun, effective, and 100% free language learning.</p>
                <a href="{{ route('lessons') }}"
                    class="bg-white text-[#18AEB5] font-bold py-3 px-8 rounded-2xl text-lg hover:bg-gray-100 transition">
                    Get Started
                </a>
            </div>
        </div>
    </div>

    <!-- Language Selection -->
    <div class="max-w-5xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold text-center mb-8">Choose a language to learn</h2>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <!-- Language Cards -->
            <a href="{{ route('lessons') }}"
                class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center hover:shadow-lg transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Flag_of_the_United_Kingdom_%283-5%29.svg/330px-Flag_of_the_United_Kingdom_%283-5%29.svg.png"
                    alt="English Flag" class="w-16 rounded-full mb-3">
                <span class="font-bold">English</span>
                <span class="text-sm text-gray-500">Most Popular</span>
            </a>

            <a href="{{ route('lessons') }}"
                class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center hover:shadow-lg transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/Flag_of_France.svg/330px-Flag_of_France.svg.png"
                    alt="French Flag" class="w-16 rounded-full mb-3">
                <span class="font-bold">French</span>
            </a>

            <a href="{{ route('lessons') }}"
                class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center hover:shadow-lg transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Flag_of_Germany.svg/320px-Flag_of_Germany.svg.png"
                    alt="German Flag" class="w-16 rounded-full mb-3">
                <span class="font-bold">German</span>
            </a>

            <a href="{{ route('lessons') }}"
                class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center hover:shadow-lg transition">
                <img src="https://upload.wikimedia.org/wikipedia/en/thumb/0/03/Flag_of_Italy.svg/330px-Flag_of_Italy.svg.png"
                    alt="Italian Flag" class="w-16 rounded-full mb-3">
                <span class="font-bold">Italian</span>
            </a>

            <a href="{{ route('lessons') }}"
                class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center hover:shadow-lg transition">
                <img src="https://upload.wikimedia.org/wikipedia/en/thumb/9/9e/Flag_of_Japan.svg/330px-Flag_of_Japan.svg.png"
                    alt="Japanese Flag" class="w-16 rounded-full mb-3">
                <span class="font-bold">Japanese</span>
            </a>

            <a href="{{ route('lessons') }}"
                class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center hover:shadow-lg transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/09/Flag_of_South_Korea.svg/330px-Flag_of_South_Korea.svg.png"
                    alt="Korean Flag" class="w-16 rounded-full mb-3">
                <span class="font-bold">Korean</span>
            </a>

            <a href="{{ route('lessons') }}"
                class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center hover:shadow-lg transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_the_People%27s_Republic_of_China.svg/330px-Flag_of_the_People%27s_Republic_of_China.svg.png"
                    alt="Chinese Flag" class="w-16 rounded-full mb-3">
                <span class="font-bold">Chinese</span>
            </a>

            <a href="{{ route('lessons') }}"
                class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center hover:shadow-lg transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Flag_of_Portugal.svg/330px-Flag_of_Portugal.svg.png"
                    alt="Portuguese Flag" class="w-16 rounded-full mb-3">
                <span class="font-bold">Portuguese</span>
            </a>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-gray-50 py-12">
        <div class="max-w-5xl mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-12">Why choose Qudiviz?</h2>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-md p-6 flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-[#99E5E0] rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-gamepad text-2xl text-[#004A68]"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Fun and Engaging</h3>
                    <p class="text-gray-600">Learn through interactive games and challenges that make language learning
                        enjoyable.
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-[#99E5E0] rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-brain text-2xl text-[#004A68]"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Science-Based</h3>
                    <p class="text-gray-600">Our methods are backed by research to ensure effective and efficient learning.
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-[#99E5E0] rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-users text-2xl text-[#004A68]"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Community</h3>
                    <p class="text-gray-600">Join millions of learners worldwide and compete on our leaderboards.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="max-w-5xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold text-center mb-8">What our users say</h2>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="User"
                        class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-bold">Sarah Johnson</h4>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">"I've tried many language apps, but Qudiviz is by far the most engaging. I've
                    learned
                    more English in 3 months than I did in years of school!"</p>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/men/35.jpg" alt="User"
                        class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-bold">Owen Shaw</h4>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">"Qudiviz is so cool, i can learn English just at Home. Good Job!"</p>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center mb-4">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User"
                        class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-bold">Michael Chen</h4>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">"The gamification aspect keeps me motivated. I've maintained a 65-day streak
                    learning
                    French and can now have basic conversations!"</p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-[#18AEB5] text-white py-12">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to start your language journey?</h2>
            <p class="text-xl mb-8">Join over 500 million learners today.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register.index') }}"
                    class="bg-white text-[#18AEB5] font-bold py-3 px-8 rounded-2xl text-lg hover:bg-gray-100 transition">
                    Create Account
                </a>
                <a href="{{ route('login') }}"
                    class="bg-transparent border-2 border-white text-white font-bold py-3 px-8 rounded-2xl text-lg hover:bg-[#006B87] transition">
                    Log In
                </a>
            </div>
        </div>
    </div>
@endsection
