@extends('layout.customer')

@section('title', 'My Saved Addresses')

@section('content')

<style>
    .modal-overlay {
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    transform: scale(0.7) translateY(-50px);
    opacity: 0;
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
}

.modal-overlay.show .modal-content {
    transform: scale(1) translateY(0);
    opacity: 1;
}

.modal-overlay.closing {
    opacity: 0;
    visibility: hidden;
}

.modal-overlay.closing .modal-content {
    transform: scale(0.7) translateY(-50px);
    opacity: 0;
}


</style>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                     <h1 class="text-xl sm:text-2xl font-bold text-gray-900">My Saved Addresses</h1>
                    <p class="mt-1 text-xs sm:text-sm text-gray-600">Manage your delivery addresses for faster checkout</p>
                </div>
                <button 
    type="button"
    onclick="openCreateModal()"
    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-medium rounded-lg shadow-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto w-full hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
    </svg>
    Add New Address
</button>
            </div>
        </div>

        <!-- Addresses Grid -->
        @if($addresses->count() > 0)
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($addresses as $address)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 overflow-hidden">
                        <!-- Address Header -->
                        <div class="px-6 py-4 border-b border-gray-100">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $address->address_label }}</h3>
                                    @if($address->is_default)
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Default Address
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Actions Dropdown -->
                                <div class="relative ml-4">
                                    <button type="button" onclick="toggleDropdown('dropdown-{{ $address->id }}')" 
                                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                            aria-label="Address options">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                        </svg>
                                    </button>
                                    
                                    <div id="dropdown-{{ $address->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                        <div class="py-1">
                                            <button 
                                              onclick="openEditModal({{ $address->id }})" 
                                              type="button"
                                              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900">
                                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                                 Edit Address
                                            </button>
                                           <button type="button" 
                                                    onclick="openDeleteModal({{ $address->id }})"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Delete Address
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Details -->
                        <div class="px-6 py-4 space-y-3">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div class="text-sm text-gray-700">
                                    <p class="font-medium">{{ $address->address_line1 }}</p>
                                    <p class="text-gray-600">{{ $address->district->name }}</p>
                                </div>
                            </div>

                            @if($address->delivery_notes)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                    <p class="text-sm text-gray-600">{{ $address->delivery_notes }}</p>
                                </div>
                            @endif
                        </div>

                       <!--Edit Modal-->
                       @include('customer.my-account.saved-addresses.modal.edit-modal')

                        <!--Delete Modal-->
                       @include('customer.my-account.saved-addresses.modal.delete-modal')

                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No saved addresses yet</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">Save your frequently used addresses to make checkout faster and easier.</p>
                         <button 
                            type="button"
                            onclick="openCreateModal()"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:bg-emerald     -600 text-white font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Your First Address
                        </button>
            </div>
        @endif
    </div>
</div>

<!-- Create Address Modal -->
  @include('customer.my-account.saved-addresses.modal.create-modal')



<script>
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
    
    // Close all other dropdowns
    allDropdowns.forEach(dd => {
        if (dd.id !== dropdownId) {
            dd.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const isDropdownButton = event.target.closest('[onclick*="toggleDropdown"]');
    const isDropdownContent = event.target.closest('[id^="dropdown-"]');
    
    if (!isDropdownButton && !isDropdownContent) {
        const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
        allDropdowns.forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }
});
   function openEditModal(id) {
    const modal = document.getElementById('edit-modal-' + id);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Add animation class after a short delay
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
}

function closeEditModal(id) {
    const modal = document.getElementById('edit-modal-' + id);
    
    modal.classList.remove('show'); // remove show first if you’re animating
    modal.classList.add('closing'); // optional, if you use a closing animation
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex', 'closing');
    }, 300); // match this with your CSS transition duration
}

    
    function openCreateModal() {
        const modal = document.getElementById('create-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
            setTimeout(() => modal.classList.add('show'), 10);

    }

    function closeCreateModal() {
        const modal = document.getElementById('create-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex', 'closing');
    }, 300);
    }

    function openDeleteModal(id) {
        const modal = document.getElementById('delete-modal-' + id);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
            setTimeout(() => modal.classList.add('show'), 10);

    }

    function closeDeleteModal(id) {
        const modal = document.getElementById('delete-modal-' + id);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex', 'closing');
    }, 300);
    }
</script>
@endsection