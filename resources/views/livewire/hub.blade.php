<div class="w-full max-w-3xl">
    <div class="text-center mb-10">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">What do you want to do?</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Choose a system to continue.</p>
    </div>

    <div class="grid sm:grid-cols-2 gap-6">
        <a href="{{ route('expenses.dashboard') }}" wire:navigate
           class="group block rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-8 shadow-sm hover:shadow-lg hover:border-blue-400 transition-all">
            <div class="w-12 h-12 rounded-xl bg-blue-600/10 text-blue-600 flex items-center justify-center mb-5 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Expense Tracker</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track spending, categories, and reports.</p>
        </a>

        <a href="{{ route('inventory.dashboard') }}" wire:navigate
           class="group block rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-8 shadow-sm hover:shadow-lg hover:border-emerald-400 transition-all">
            <div class="w-12 h-12 rounded-xl bg-emerald-600/10 text-emerald-600 flex items-center justify-center mb-5 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Point of Sale</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sell products, manage stock, FIFO costing.</p>
        </a>
    </div>
</div>
