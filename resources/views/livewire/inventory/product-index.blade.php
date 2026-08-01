<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Products</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Manage products and record incoming stock.</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        {{ $this->table }}
    </div>

    <x-filament-actions::modals />
</div>
