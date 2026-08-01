<div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Inventory Dashboard</h2>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Today's Sales</div>
            <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">GHS {{ number_format($this->todaysSales, 2) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Stock Value</div>
            <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">GHS {{ number_format($this->stockValue, 2) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Low Stock Items</div>
            <div class="mt-1 text-2xl font-bold {{ $this->lowStockCount > 0 ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">{{ $this->lowStockCount }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Active Products</div>
            <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $this->productCount }}</div>
        </div>
    </div>

    <div class="grid sm:grid-cols-3 gap-4">
        <a href="{{ route('inventory.pos') }}" wire:navigate class="block p-5 rounded-xl bg-emerald-600 text-white font-medium text-center hover:bg-emerald-700 transition-colors">
            Start a Sale
        </a>
        <a href="{{ route('inventory.products') }}" wire:navigate class="block p-5 rounded-xl bg-white dark:bg-gray-800 shadow text-gray-900 dark:text-white font-medium text-center hover:shadow-md transition-shadow">
            Manage Products
        </a>
        <a href="{{ route('inventory.reports') }}" wire:navigate class="block p-5 rounded-xl bg-white dark:bg-gray-800 shadow text-gray-900 dark:text-white font-medium text-center hover:shadow-md transition-shadow">
            View Reports
        </a>
    </div>
</div>
