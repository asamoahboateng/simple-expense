<div class="max-w-lg mx-auto">
    <div class="flex items-center justify-between mb-6 print:hidden">
        <a href="{{ route('inventory.sales') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            &larr; Back to sales
        </a>
        <button onclick="window.print()" class="text-sm px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
            Print
        </button>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Receipt {{ $sale->sale_number }}</p>
            @if ($sale->status === 'voided')
                <span class="inline-block mt-2 px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">VOIDED</span>
            @endif
        </div>

        <div class="text-sm text-gray-600 dark:text-gray-400 mb-4 space-y-0.5">
            <div>Date: {{ $sale->sold_at->format('M j, Y g:i A') }}</div>
            <div>Sold by: {{ $sale->soldBy?->name ?? '—' }}</div>
            @if ($sale->customer_name)
                <div>Customer: {{ $sale->customer_name }}</div>
            @endif
        </div>

        <table class="w-full text-sm mb-4">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700 text-left text-gray-500 dark:text-gray-400">
                    <th class="py-2">Item</th>
                    <th class="py-2 text-center">Qty</th>
                    <th class="py-2 text-right">Price</th>
                    <th class="py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr class="border-b border-gray-100 dark:border-gray-700/50">
                        <td class="py-2">
                            {{ $item->product?->name ?? 'Deleted product' }}
                            <div class="text-xs text-gray-400">
                                Batch{{ $item->consumptions->count() > 1 ? 'es' : '' }}:
                                {{ $item->consumptions->map(fn ($c) => "{$c->stockLot->batch_number} ({$c->quantity})")->join(', ') }}
                            </div>
                        </td>
                        <td class="py-2 text-center">{{ $item->quantity }}</td>
                        <td class="py-2 text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-2 text-right">{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex items-center justify-between text-lg font-bold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-700">
            <span>Total</span>
            <span>GHS {{ number_format($sale->total_amount, 2) }}</span>
        </div>
    </div>
</div>
