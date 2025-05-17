<div id="toast-container"
    class="fixed top-4 right-4 z-50 max-w-xs w-full md:max-w-sm transform transition-all duration-300 ease-in-out translate-x-full hidden">
    <div id="toast" class="rounded-lg shadow-lg overflow-hidden">
        <div id="toast-content" class="flex items-center p-4">
            <div id="toast-icon" class="flex-shrink-0 w-6 h-6 mr-3 flex items-center justify-center">
                <i class="fas"></i>
            </div>
            <div class="flex-grow">
                <p id="toast-message" class="text-sm font-medium"></p>
            </div>
            <div class="ml-3 flex-shrink-0">
                <button id="toast-close" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div id="toast-progress" class="h-1 w-full bg-opacity-50"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toastContainer = document.getElementById('toast-container');
        const toast = document.getElementById('toast');
        const toastContent = document.getElementById('toast-content');
        const toastIcon = document.getElementById('toast-icon').querySelector('i');
        const toastMessage = document.getElementById('toast-message');
        const toastClose = document.getElementById('toast-close');
        const toastProgress = document.getElementById('toast-progress');

        let progressInterval;
        let autoCloseTimeout;

        // Function to show toast
        window.showToast = function(message, type = 'success', duration = 5000) {
            // Clear any existing timeouts/intervals
            clearInterval(progressInterval);
            clearTimeout(autoCloseTimeout);

            // Set toast type (success, error, info, warning)
            setToastType(type);

            // Set message
            toastMessage.textContent = message;

            // Show toast
            toastContainer.classList.remove('translate-x-full');
            toastContainer.classList.remove('hidden');
            toastContainer.classList.add('translate-x-0');

            // Start progress bar
            let progress = 0;
            const increment = 100 / (duration / 10); // Update every 10ms

            toastProgress.style.width = '100%';

            progressInterval = setInterval(() => {
                progress += increment;
                const remaining = 100 - progress;
                toastProgress.style.width = `${remaining}%`;

                if (progress >= 100) {
                    clearInterval(progressInterval);
                }
            }, 10);

            // Auto close after duration
            autoCloseTimeout = setTimeout(() => {
                hideToast();
            }, duration);
        };

        // Function to hide toast
        function hideToast() {
            toastContainer.classList.remove('translate-x-0');
            toastContainer.classList.add('translate-x-full');
            toastContainer.classList.add('hidden');
            clearInterval(progressInterval);
            clearTimeout(autoCloseTimeout);
        }

        // Set toast type (changes colors and icon)
        function setToastType(type) {
            // Reset classes
            toast.className = 'rounded-lg shadow-lg overflow-hidden';
            toastIcon.className = 'fas';
            toastProgress.className = 'h-1 w-full bg-opacity-50';

            // Set new classes based on type
            switch (type) {
                case 'success':
                    toast.classList.add('bg-green-50');
                    toastContent.classList.add('text-green-800');
                    toastIcon.classList.add('fa-check-circle', 'text-green-500');
                    toastProgress.classList.add('bg-green-500');
                    break;
                case 'error':
                    toast.classList.add('bg-red-50');
                    toastContent.classList.add('text-red-800');
                    toastIcon.classList.add('fa-exclamation-circle', 'text-red-500');
                    toastProgress.classList.add('bg-red-500');
                    break;
                case 'warning':
                    toast.classList.add('bg-yellow-50');
                    toastContent.classList.add('text-yellow-800');
                    toastIcon.classList.add('fa-exclamation-triangle', 'text-yellow-500');
                    toastProgress.classList.add('bg-yellow-500');
                    break;
                case 'info':
                    toast.classList.add('bg-blue-50');
                    toastContent.classList.add('text-blue-800');
                    toastIcon.classList.add('fa-info-circle', 'text-blue-500');
                    toastProgress.classList.add('bg-blue-500');
                    break;
            }
        }

        // Close button event
        toastClose.addEventListener('click', hideToast);

        // Check for flash messages on page load
        @if (session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif

        @if (session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif

        @if (session('warning'))
            showToast("{{ session('warning') }}", 'warning');
        @endif

        @if (session('info'))
            showToast("{{ session('info') }}", 'info');
        @endif
    });
</script>
