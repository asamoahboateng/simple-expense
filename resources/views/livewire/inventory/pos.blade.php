<div class="grid lg:grid-cols-3 gap-6">
    {{-- Product picker --}}
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Checkout</h2>

        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name or SKU..."
               class="w-full p-2.5 mb-4 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">

        <div class="grid sm:grid-cols-2 gap-3">
            @forelse ($products as $product)
                <button type="button" wire:click="addToCart({{ $product->id }})"
                        @if($product->quantity_on_hand <= 0) disabled @endif
                        class="text-left p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                    <div class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $product->sku }} &middot; {{ $product->quantity_on_hand }} {{ $product->unit }} in stock</div>
                    <div class="mt-1 text-sm font-semibold text-emerald-600">GHS {{ number_format($product->sale_price, 2) }}</div>
                </button>
            @empty
                <p class="text-sm text-gray-500 dark:text-gray-400 col-span-2">No products found.</p>
            @endforelse
        </div>
    </div>

    {{-- Cart --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Cart</h3>

        <div class="flex-1 space-y-3 mb-4">
            @forelse ($cart as $productId => $line)
                <div class="flex items-center justify-between gap-2 text-sm">
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-gray-900 dark:text-white truncate">{{ $line['name'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">GHS {{ number_format($line['unit_price'], 2) }} each</div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button type="button" wire:click="decrementQty({{ $productId }})" class="w-6 h-6 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">-</button>
                        <span class="w-6 text-center">{{ $line['quantity'] }}</span>
                        <button type="button" wire:click="incrementQty({{ $productId }})" class="w-6 h-6 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">+</button>
                    </div>
                    <button type="button" wire:click="removeFromCart({{ $productId }})" class="text-red-500 hover:text-red-700 text-xs">✕</button>
                </div>
            @empty
                <p class="text-sm text-gray-400">Cart is empty — click a product to add it.</p>
            @endforelse
        </div>

        <div>
            <input wire:model="customerName" type="text" placeholder="Customer name (optional)"
                   class="w-full p-2 mb-3 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">

            <div class="flex items-center justify-between text-lg font-bold text-gray-900 dark:text-white mb-4">
                <span>Total</span>
                <span>GHS {{ number_format($this->cartTotal, 2) }}</span>
            </div>

            <button type="button" wire:click="completeSale"
                    wire:loading.attr="disabled"
                    class="w-full py-2.5 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition-colors disabled:opacity-75">
                Complete Sale
            </button>
        </div>
    </div>
</div>
