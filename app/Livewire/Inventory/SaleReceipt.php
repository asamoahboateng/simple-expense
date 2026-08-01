<?php

namespace App\Livewire\Inventory;

use App\Models\Sale;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.inventory')]
#[Title('Receipt')]
class SaleReceipt extends Component
{
    public Sale $sale;

    public function mount(Sale $sale): void
    {
        $this->sale = $sale->load('items.product', 'items.consumptions.stockLot', 'soldBy');
    }

    public function render()
    {
        return view('livewire.inventory.sale-receipt');
    }
}
