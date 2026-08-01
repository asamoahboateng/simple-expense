<?php

namespace App\Livewire\Inventory;

use App\Exceptions\InsufficientStockException;
use App\Models\Product;
use App\Services\InventoryService;
use Filament\Notifications\Notification;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.inventory')]
#[Title('Checkout')]
class Pos extends Component
{
    public string $search = '';

    public array $cart = [];

    public string $customerName = '';

    public function addToCart(int $productId): void
    {
        $product = Product::findOrFail($productId);

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
            return;
        }

        $this->cart[$productId] = [
            'name' => $product->name,
            'sku' => $product->sku,
            'unit_price' => (float) $product->sale_price,
            'quantity' => 1,
            'available' => $product->quantity_on_hand,
        ];
    }

    public function incrementQty(int $productId): void
    {
        $this->cart[$productId]['quantity']++;
    }

    public function decrementQty(int $productId): void
    {
        if ($this->cart[$productId]['quantity'] <= 1) {
            $this->removeFromCart($productId);
            return;
        }

        $this->cart[$productId]['quantity']--;
    }

    public function removeFromCart(int $productId): void
    {
        unset($this->cart[$productId]);
    }

    public function getProductsProperty()
    {
        return Product::query()
            ->where('is_active', true)
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('sku', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('name')
            ->limit(20)
            ->get();
    }

    public function getCartTotalProperty(): float
    {
        return collect($this->cart)->sum(fn ($line) => $line['unit_price'] * $line['quantity']);
    }

    public function completeSale(): void
    {
        if (empty($this->cart)) {
            Notification::make()->title('Cart is empty')->danger()->send();
            return;
        }

        $cartItems = collect($this->cart)->map(fn ($line, $productId) => [
            'product_id' => $productId,
            'quantity' => $line['quantity'],
            'unit_price' => $line['unit_price'],
        ])->values()->all();

        try {
            $sale = InventoryService::checkout($cartItems, $this->customerName ?: null, auth()->id());
        } catch (InsufficientStockException $e) {
            Notification::make()->title('Sale failed')->body($e->getMessage())->danger()->send();
            return;
        }

        $this->cart = [];
        $this->customerName = '';

        $this->redirect(route('inventory.sales.show', $sale));
    }

    public function render()
    {
        return view('livewire.inventory.pos', [
            'products' => $this->products,
        ]);
    }
}
