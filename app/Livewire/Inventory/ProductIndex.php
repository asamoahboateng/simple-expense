<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use App\Services\InventoryService;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.inventory')]
#[Title('Products')]
class ProductIndex extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query())
            ->columns([
                TextColumn::make('sku')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable()->weight('medium'),
                TextColumn::make('quantity_on_hand')
                    ->label('Stock')
                    ->sortable()
                    ->color(fn (Product $record) => $record->quantity_on_hand <= $record->reorder_level ? 'danger' : 'success')
                    ->suffix(fn (Product $record) => ' '.$record->unit),
                TextColumn::make('sale_price')->money('GHS')->sortable(),
                IconColumn::make('is_active')->boolean()->label('Active'),
            ])
            ->headerActions([
                $this->createProductAction(),
            ])
            ->recordActions([
                $this->viewBatchesAction(),
                $this->recordStockAction(),
                $this->editProductAction(),
                DeleteAction::make()->requiresConfirmation(),
            ])
            ->defaultSort('name')
            ->striped();
    }

    protected function productForm(): array
    {
        return [
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('sku')->label('SKU')->required()->maxLength(255),
            Textarea::make('description')->rows(2),
            TextInput::make('unit')->default('pcs')->required(),
            TextInput::make('sale_price')->numeric()->prefix('GHS')->required(),
            TextInput::make('reorder_level')->numeric()->default(0)->label('Low stock alert level'),
            Toggle::make('is_active')->default(true),
        ];
    }

    public function createProductAction(): Action
    {
        return Action::make('createProduct')
            ->label('New Product')
            ->modalHeading('Create Product')
            ->form($this->productForm())
            ->action(function (array $data): void {
                Product::create($data);
                Notification::make()->title('Product created')->success()->send();
            });
    }

    public function editProductAction(): Action
    {
        return Action::make('editProduct')
            ->label('Edit')
            ->modalHeading('Edit Product')
            ->form($this->productForm())
            ->fillForm(fn (Product $record): array => $record->toArray())
            ->action(function (Product $record, array $data): void {
                $record->update($data);
                Notification::make()->title('Product updated')->success()->send();
            });
    }

    public function recordStockAction(): Action
    {
        return Action::make('recordStock')
            ->label('Record Stock')
            ->color('success')
            ->modalHeading(fn (Product $record) => "Record stock for {$record->name}")
            ->form([
                TextInput::make('quantity')->numeric()->required()->minValue(1),
                TextInput::make('unit_cost')->numeric()->prefix('GHS')->required()->minValue(0),
                TextInput::make('supplier')->maxLength(255),
                DatePicker::make('purchased_at')->label('Date')->default(now())->required(),
            ])
            ->action(function (Product $record, array $data): void {
                InventoryService::recordPurchase(
                    product: $record,
                    quantity: (int) $data['quantity'],
                    unitCost: (float) $data['unit_cost'],
                    supplier: $data['supplier'] ?? null,
                    recordedBy: auth()->id(),
                    purchasedAt: $data['purchased_at'],
                );
                Notification::make()->title('Stock recorded')->success()->send();
            });
    }

    public function viewBatchesAction(): Action
    {
        return Action::make('viewBatches')
            ->label('Batches')
            ->color('gray')
            ->modalHeading(fn (Product $record) => "Stock batches — {$record->name}")
            ->modalContent(fn (Product $record) => view('livewire.inventory.partials.batches-modal', [
                'lots' => $record->stockLots()->orderBy('purchased_at')->orderBy('id')->get(),
            ]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Close');
    }

    public function render()
    {
        return view('livewire.inventory.product-index');
    }
}
