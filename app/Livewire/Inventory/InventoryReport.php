<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.inventory')]
#[Title('Inventory Reports')]
class InventoryReport extends Component
{
    public string $dateFrom = '';

    public string $dateTo = '';

    public function mount(): void
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    private function salesInRange()
    {
        return Sale::query()
            ->where('status', 'completed')
            ->whereBetween('sold_at', ["{$this->dateFrom} 00:00:00", "{$this->dateTo} 23:59:59"]);
    }

    public function getRevenueProperty(): float
    {
        return (float) $this->salesInRange()->sum('total_amount');
    }

    public function getCogsProperty(): float
    {
        return (float) $this->salesInRange()->sum('total_cost');
    }

    public function getProfitProperty(): float
    {
        return $this->revenue - $this->cogs;
    }

    public function getStockValuationProperty()
    {
        return Product::query()
            ->where('is_active', true)
            ->get()
            ->map(function (Product $product) {
                $value = $product->stockLots()
                    ->select(DB::raw('SUM(quantity_remaining * unit_cost) as value'))
                    ->value('value') ?? 0;

                return (object) [
                    'product' => $product,
                    'value' => (float) $value,
                ];
            })
            ->sortByDesc('value')
            ->values();
    }

    public function getTotalStockValueProperty(): float
    {
        return $this->stockValuation->sum('value');
    }

    public function render()
    {
        return view('livewire.inventory.inventory-report');
    }
}
