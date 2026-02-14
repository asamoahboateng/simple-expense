<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use App\Models\MainCategory;
use App\Services\CategoryService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Expense')]
class ExpenseForm extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];
    public ?Expense $expense = null;

    public function mount(?Expense $expense = null): void
    {
        $this->expense = $expense;

        if ($expense?->exists) {
            $this->form->fill($expense->toArray());
        } else {
            $this->form->fill([
                'person' => auth()->user()->name,
                'expense_date' => now()->format('Y-m-d'),
            ]);
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Description / Narrative')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->prefix('GHS')
                    ->minValue(0.01)
                    ->step(0.01),
                TextInput::make('person')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Defaults to you. Change if someone else made this expense.'),
                Select::make('main_category_id')
                    ->label('Main Category')
                    ->options(MainCategory::orderBy('name')->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('sub_category_id', null))
                    ->searchable(),
                Select::make('sub_category_id')
                    ->label('Subcategory')
                    ->options(function (callable $get) {
                        $mainCategoryId = $get('main_category_id');
                        if (! $mainCategoryId) {
                            return [];
                        }
                        return CategoryService::getSubCategoryOptionsForMainCategory($mainCategoryId);
                    })
                    ->searchable()
                    ->placeholder('Select subcategory (optional)'),
                DatePicker::make('expense_date')
                    ->required()
                    ->maxDate(now())
                    ->default(now()),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['user_id'] = auth()->id();

        if ($this->expense?->exists) {
            $this->expense->update($data);
            Notification::make()->title('Expense updated')->success()->send();
        } else {
            Expense::create($data);
            Notification::make()->title('Expense created')->success()->send();
        }

        $this->redirect(route('expenses.index'));
    }

    public function render()
    {
        return view('livewire.expenses.expense-form');
    }
}
