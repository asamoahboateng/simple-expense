<div class="space-y-2">
    <p class="text-sm text-gray-500 dark:text-gray-400">Oldest first — this is the order stock is sold from (FIFO).</p>

    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200 dark:border-gray-700 text-left text-gray-500 dark:text-gray-400">
                <th class="py-2">Batch #</th>
                <th class="py-2">Date</th>
                <th class="py-2 text-right">Remaining</th>
                <th class="py-2 text-right">Purchased</th>
                <th class="py-2 text-right">Unit cost</th>
                <th class="py-2">Supplier</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lots as $lot)
                <tr class="border-b border-gray-100 dark:border-gray-700/50 {{ $lot->quantity_remaining <= 0 ? 'opacity-40' : '' }}">
                    <td class="py-2 font-medium text-gray-900 dark:text-white">{{ $lot->batch_number }}</td>
                    <td class="py-2">{{ $lot->purchased_at->format('M j, Y') }}</td>
                    <td class="py-2 text-right">{{ $lot->quantity_remaining }}</td>
                    <td class="py-2 text-right">{{ $lot->quantity }}</td>
                    <td class="py-2 text-right">GHS {{ number_format($lot->unit_cost, 2) }}</td>
                    <td class="py-2">{{ $lot->supplier ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-4 text-center text-gray-400">No stock batches recorded yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
