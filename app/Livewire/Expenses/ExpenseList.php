<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Expenses')]
class ExpenseList extends Component implements HasTable, HasActions, HasSchemas
{
    use InteractsWithTable;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Expense::query()
                    ->where('user_id', auth()->id())
                    ->with(['mainCategory', 'subCategory'])
            )
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make('cost')
                    ->money('GHS')
                    ->sortable()
                    ->color('danger'),
                TextColumn::make('person')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mainCategory.name')
                    ->label('Category')
                    ->sortable()
                    ->badge(),
                TextColumn::make('subCategory.name')
                    ->label('Subcategory')
                    ->placeholder('—'),
                TextColumn::make('expense_date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('main_category_id')
                    ->relationship('mainCategory', 'name')
                    ->label('Category')
                    ->preload(),
                Filter::make('expense_date')
                    ->form([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn (Builder $q, $date) => $q->whereDate('expense_date', '>=', $date))
                            ->when($data['until'], fn (Builder $q, $date) => $q->whereDate('expense_date', '<=', $date));
                    }),
                Filter::make('person')
                    ->form([
                        TextInput::make('person')->label('Person'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['person'], fn (Builder $q, $person) => $q->where('person', 'like', "%{$person}%"));
                    }),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->recordUrl(fn (Expense $record) => route('expenses.edit', $record))
            ->defaultSort('expense_date', 'desc')
            ->striped();
    }

    public function render()
    {
        return view('livewire.expenses.expense-list');
    }
}
