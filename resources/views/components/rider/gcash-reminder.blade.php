@if(Auth::check() && Auth::user()->role === 'rider' && Auth::user()->rider && Auth::user()->rider->hasIncompleteGCashInfo())
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 sm:p-4 mb-4 sm:mb-6 rounded-r-lg shadow-sm" id="gcash-reminder">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3 flex-1">
            <h3 class="text-xs sm:text-sm font-medium text-yellow-800">
                GCash Information Required
            </h3>
            <div class="mt-1 sm:mt-2 text-xs sm:text-sm text-yellow-700">
                <p class="hidden sm:block">Please complete your GCash information to receive payouts. You need to provide:</p>
                <p class="sm:hidden">Complete your GCash info to receive payouts:</p>
                <ul class="list-disc list-inside mt-1 sm:mt-2 space-y-0.5 sm:space-y-1">
                    @if(empty(Auth::user()->rider->gcash_number))
                    <li>GCash Mobile Number</li>
                    @endif
                    @if(empty(Auth::user()->rider->gcash_name))
                    <li>GCash Account Name</li>
                    @endif
                    @if(empty(Auth::user()->rider->gcash_qr_path))
                    <li>GCash QR Code</li>
                    @endif
                </ul>
            </div>
            <div class="mt-2 sm:mt-4">
                <a href="{{ route('rider.profile.edit') }}" class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 border border-transparent text-xs sm:text-sm font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                    <svg class="mr-1 sm:mr-2 h-3 w-3 sm:h-4 sm:w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="hidden sm:inline">Complete GCash Information</span>
                    <span class="sm:hidden">Complete Info</span>
                </a>
            </div>
        </div>
        <div class="ml-2 sm:ml-auto sm:pl-3">
            <button type="button" onclick="dismissReminder()" class="inline-flex text-yellow-400 hover:text-yellow-600 focus:outline-none focus:text-yellow-600 transition ease-in-out duration-150">
                <svg class="h-4 w-4 sm:h-5 sm:w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
    function dismissReminder() {
        const reminder = document.getElementById('gcash-reminder');
        if (reminder) {
            // Store dismissal in session storage (will reappear on page refresh)
            sessionStorage.setItem('gcash-reminder-dismissed', 'true');
            reminder.style.display = 'none';
        }
    }

    // Check if reminder was dismissed in this session
    document.addEventListener('DOMContentLoaded', function() {
        const reminder = document.getElementById('gcash-reminder');
        if (reminder && sessionStorage.getItem('gcash-reminder-dismissed') === 'true') {
            reminder.style.display = 'none';
        }
    });
</script>
@endif
