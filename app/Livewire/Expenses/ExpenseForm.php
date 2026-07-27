<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Services\CategoryService;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\ColorPicker;
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
class ExpenseForm extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];
    public ?Expense $expense = null;

    public function mount(?Expense $expense = null): void
    {
        $this->expense = $expense;

        if ($expense?->exists) {
            if ($expense->user_id !== auth()->id()) {
                abort(403);
            }
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
                    ->options(fn () => MainCategory::orderBy('name')->pluck('name', 'id'))
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('sub_category_id', null))
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('name')->required()->maxLength(255),
                        Textarea::make('description')->rows(2),
                        TextInput::make('icon')->placeholder('e.g. shopping-bag'),
                        ColorPicker::make('color'),
                    ])
                    ->createOptionUsing(fn (array $data) => MainCategory::create($data)->getKey()),
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
                    ->placeholder('Select subcategory (optional)')
                    ->disabled(fn (callable $get) => ! $get('main_category_id'))
                    ->helperText(fn (callable $get) => $get('main_category_id') ? null : 'Select a main category first.')
                    ->createOptionForm(function (callable $get) {
                        $mainCategoryId = $get('main_category_id');

                        return [
                            TextInput::make('name')->required()->maxLength(255),
                            Textarea::make('description')->rows(2),
                            Select::make('parent_id')
                                ->label('Parent Subcategory')
                                ->options(fn () => $mainCategoryId
                                    ? SubCategory::where('main_category_id', $mainCategoryId)->get()->mapWithKeys(fn ($s) => [$s->id => $s->breadcrumb])
                                    : [])
                                ->placeholder('None (root level)')
                                ->searchable(),
                        ];
                    })
                    ->createOptionUsing(function (array $data, callable $get) {
                        return SubCategory::create([
                            ...$data,
                            'main_category_id' => $get('main_category_id'),
                        ])->getKey();
                    }),
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
