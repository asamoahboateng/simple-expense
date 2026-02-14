<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Expenses</h2>
        <a href="{{ route('expenses.create') }}" wire:navigate
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg font-medium text-sm hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Expense
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
        {{ $this->table }}
    </div>
</div>
