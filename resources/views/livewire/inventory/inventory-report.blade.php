<div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Inventory Reports</h2>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
        <div class="flex flex-wrap items-end gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From</label>
                <input wire:model.live="dateFrom" type="date" class="p-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Until</label>
                <input wire:model.live="dateTo" type="date" class="p-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
            </div>
        </div>

        <div class="grid sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Revenue</div>
                <div class="mt-1 text-xl font-bold text-gray-900 dark:text-white">GHS {{ number_format($this->revenue, 2) }}</div>
            </div>
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Cost of Goods Sold (FIFO)</div>
                <div class="mt-1 text-xl font-bold text-gray-900 dark:text-white">GHS {{ number_format($this->cogs, 2) }}</div>
            </div>
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Profit</div>
                <div class="mt-1 text-xl font-bold {{ $this->profit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">GHS {{ number_format($this->profit, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Stock Valuation</h3>
            <span class="text-sm text-gray-500 dark:text-gray-400">Total: GHS {{ number_format($this->totalStockValue, 2) }}</span>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700 text-left text-gray-500 dark:text-gray-400">
                    <th class="py-2">Product</th>
                    <th class="py-2 text-right">Qty on hand</th>
                    <th class="py-2 text-right">Value (FIFO cost)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->stockValuation as $row)
                    <tr class="border-b border-gray-100 dark:border-gray-700/50">
                        <td class="py-2">{{ $row->product->name }}</td>
                        <td class="py-2 text-right">{{ $row->product->quantity_on_hand }} {{ $row->product->unit }}</td>
                        <td class="py-2 text-right">GHS {{ number_format($row->value, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
