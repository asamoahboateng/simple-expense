<div>
    <div class="mb-6">
        <a href="{{ route('expenses.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to expenses
        </a>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
            {{ $this->expense?->exists ? 'Edit Expense' : 'New Expense' }}
        </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <form wire:submit="save">
            {{ $this->form }}

            <div class="mt-6 flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white rounded-lg font-medium text-sm hover:bg-blue-700 transition-colors"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-75">
                    <span wire:loading.remove wire:target="save">
                        {{ $this->expense?->exists ? 'Update Expense' : 'Create Expense' }}
                    </span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
                <a href="{{ route('expenses.index') }}" wire:navigate
                   class="px-4 py-2.5 text-gray-700 dark:text-gray-300 rounded-lg font-medium text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <x-filament-actions::modals />
</div>
