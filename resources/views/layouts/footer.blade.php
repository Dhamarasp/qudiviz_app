@if (request()->is('/'))
    <footer class="bg-[#004A68] text-white py-8 w-full relative bottom-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">About</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-green-500">Our Story</a></li>
                        <li><a href="#" class="hover:text-green-500">Careers</a></li>
                        <li><a href="#" class="hover:text-green-500">Mission</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4">Products</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-green-500">Qudiviz for Schools</a></li>
                        <li><a href="#" class="hover:text-green-500">Qudiviz for Business</a></li>
                        <li><a href="#" class="hover:text-green-500">Premium Subscription</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-green-500">Help Center</a></li>
                        <li><a href="#" class="hover:text-green-500">Contact Us</a></li>
                        <li><a href="#" class="hover:text-green-500">Community Guidelines</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-green-500">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-green-500">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-green-500">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <img class="h-10 w-10" src="{{ asset('images/qudiviz_logo.png') }}" alt="Qudiviz Logo">
                    <span class="ml-2 text-xl font-bold text-[#18AEB5]">Qudiviz</span>
                </div>

                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
    </footer>
@endif

@if (!request()->is('lessons/sub/study*'))
    <footer class="bg-[#004A68] text-white py-8 w-full relative bottom-0">
        <div class="mt-4 text-center text-gray-400 text-sm">
            <p>&copy; 2025 Qudiviz. All rights reserved.</p>
        </div>
    </footer>
@endif


</div>
