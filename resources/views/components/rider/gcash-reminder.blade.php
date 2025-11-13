@if(Auth::check() && Auth::user()->role === 'rider' && Auth::user()->rider && Auth::user()->rider->hasIncompleteGCashInfo())
<!-- GCash Reminder Modal -->
<div id="gcash-reminder-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" id="gcash-modal-backdrop"></div>

    <!-- Modal container -->
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Trick to center modal vertically -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <!-- Modal header with icon -->
            <div class="bg-yellow-50 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            GCash Information Required
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">
                                To receive your earnings and payouts, please complete your GCash information. You're currently missing:
                            </p>
                            <ul class="mt-3 space-y-2 text-sm text-gray-700">
                                @if(empty(Auth::user()->rider->gcash_number))
                                <li class="flex items-center">
                                    <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    GCash Mobile Number
                                </li>
                                @endif
                                @if(empty(Auth::user()->rider->gcash_name))
                                <li class="flex items-center">
                                    <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    GCash Account Name
                                </li>
                                @endif
                                @if(empty(Auth::user()->rider->gcash_qr_path))
                                <li class="flex items-center">
                                    <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    GCash QR Code
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal footer with actions -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                <a href="{{ route('rider.profile.edit') }}" 
                   onclick="closeGCashModalAndRedirect(event)"
                   class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-base font-medium text-white hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Complete Now
                </a>
                <button type="button" onclick="dismissGCashModal()" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Remind Me Later
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let gcashReminderTimeout = null;

    function showGCashModal() {
        const modal = document.getElementById('gcash-reminder-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }
    }

    function dismissGCashModal() {
        const modal = document.getElementById('gcash-reminder-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
            
            // Store dismissal timestamp
            const dismissedAt = Date.now();
            sessionStorage.setItem('gcash-reminder-dismissed-at', dismissedAt);
            
            // Clear any existing timeout
            if (gcashReminderTimeout) {
                clearTimeout(gcashReminderTimeout);
            }
            
            // Set timeout to show modal again after 30 seconds
            gcashReminderTimeout = setTimeout(function() {
                sessionStorage.removeItem('gcash-reminder-dismissed-at');
                showGCashModal();
            }, 30000); // 30 seconds
        }
    }

    function closeGCashModalAndRedirect(event) {
        // Close modal immediately
        const modal = document.getElementById('gcash-reminder-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
        }
        
        // Clear any existing timeout
        if (gcashReminderTimeout) {
            clearTimeout(gcashReminderTimeout);
        }
        
        // Clear session storage so modal doesn't reappear
        sessionStorage.removeItem('gcash-reminder-dismissed-at');
        
        // Allow default link behavior (redirect will happen naturally)
        // No need to prevent default or manually redirect
    }

    // Close modal when clicking backdrop
    document.addEventListener('click', function(event) {
        const backdrop = document.getElementById('gcash-modal-backdrop');
        if (backdrop && event.target === backdrop) {
            dismissGCashModal();
        }
    });

    // Initialize modal on page load
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('gcash-reminder-modal');
        if (!modal) return;

        // Check if we're on the profile edit page - don't show modal there
        const currentPath = window.location.pathname;
        if (currentPath.includes('/rider/profile/edit') || currentPath.includes('/rider/profile')) {
            return; // Exit early, don't show modal on profile pages
        }

        const dismissedAt = sessionStorage.getItem('gcash-reminder-dismissed-at');
        
        if (dismissedAt) {
            const timeSinceDismissal = Date.now() - parseInt(dismissedAt);
            const remainingTime = 30000 - timeSinceDismissal; // 30 seconds
            
            if (remainingTime > 0) {
                // Still within the 30-second window, schedule to show after remaining time
                gcashReminderTimeout = setTimeout(function() {
                    sessionStorage.removeItem('gcash-reminder-dismissed-at');
                    showGCashModal();
                }, remainingTime);
            } else {
                // 30 seconds have passed, show modal immediately
                sessionStorage.removeItem('gcash-reminder-dismissed-at');
                showGCashModal();
            }
        } else {
            // Never dismissed or session cleared, show modal immediately
            showGCashModal();
        }
    });

    // Clean up timeout when page unloads
    window.addEventListener('beforeunload', function() {
        if (gcashReminderTimeout) {
            clearTimeout(gcashReminderTimeout);
        }
    });
</script>
@endif
