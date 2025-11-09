<div class="relative">
    <button id="credit-card-button" type="button"
        class="p-2 rounded-full border-2 border-gray-200 bg-white hover:bg-gray-50 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 relative">
       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-gray-500 hover:text-gray-600 lucide lucide-credit-card-icon lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
        <!-- Badge -->
        @auth
        <span id="cart-badge"
            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium {{ Auth::user()->shoppingCartItems()->count() > 0 ? '' : 'hidden' }}">
            <!--{{ Auth::user()->shoppingCartItems()->count() }}-->
        </span>
        @endauth
    </button>
</div>


