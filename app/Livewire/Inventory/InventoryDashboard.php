<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockLot;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.inventory')]
#[Title('Inventory Dashboard')]
class InventoryDashboard extends Component
{
    public function getTodaysSalesProperty(): float
    {
        return (float) Sale::query()
            ->where('status', 'completed')
            ->whereDate('sold_at', now()->toDateString())
            ->sum('total_amount');
    }

    public function getStockValueProperty(): float
    {
        return (float) StockLot::query()
            ->select(DB::raw('SUM(quantity_remaining * unit_cost) as value'))
            ->value('value') ?? 0.0;
    }

    public function getLowStockCountProperty(): int
    {
        return Product::query()
            ->where('is_active', true)
            ->whereColumn('quantity_on_hand', '<=', 'reorder_level')
            ->count();
    }

    public function getProductCountProperty(): int
    {
        return Product::query()->where('is_active', true)->count();
    }

    public function render()
    {
        return view('livewire.inventory.inventory-dashboard');
    }
}
