<?php

namespace App\Livewire\Inventory;

use App\Models\Sale;
use App\Services\InventoryService;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.inventory')]
#[Title('Sales')]
class SaleHistory extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Sale::query()->with('soldBy'))
            ->columns([
                TextColumn::make('sale_number')->searchable()->weight('medium'),
                TextColumn::make('sold_at')->dateTime()->sortable(),
                TextColumn::make('soldBy.name')->label('Sold by')->placeholder('—'),
                TextColumn::make('customer_name')->label('Customer')->placeholder('—'),
                TextColumn::make('total_amount')->label('Total')->money('GHS')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => $state === 'voided' ? 'danger' : 'success'),
            ])
            ->recordActions([
                $this->voidSaleAction(),
            ])
            ->recordUrl(fn (Sale $record) => route('inventory.sales.show', $record))
            ->defaultSort('sold_at', 'desc')
            ->striped();
    }

    public function voidSaleAction(): Action
    {
        return Action::make('voidSale')
            ->label('Void')
            ->color('danger')
            ->requiresConfirmation()
            ->modalDescription('This restores the sold stock back to inventory. This cannot be undone.')
            ->visible(fn (Sale $record) => $record->status !== 'voided')
            ->action(function (Sale $record): void {
                InventoryService::voidSale($record);
                Notification::make()->title('Sale voided')->success()->send();
            });
    }

    public function render()
    {
        return view('livewire.inventory.sale-history');
    }
}
