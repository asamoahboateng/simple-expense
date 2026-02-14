<?php

namespace App\Livewire\Reports;

use App\Exports\ExpenseExport;
use App\Models\Expense;
use App\Models\MainCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\Summarizers\Sum;
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
#[Title('Reports')]
class Reports extends Component implements HasTable, HasActions, HasSchemas
{
    use InteractsWithTable;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public string $exportDateFrom = '';
    public string $exportDateTo = '';
    public ?int $exportCategoryId = null;
    public string $exportPerson = '';

    public function mount(): void
    {
        $this->exportDateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->exportDateTo = now()->format('Y-m-d');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Expense::query()
                    ->where('user_id', auth()->id())
                    ->with(['mainCategory', 'subCategory'])
            )
            ->columns([
                TextColumn::make('expense_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mainCategory.name')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('subCategory.name')
                    ->label('Subcategory')
                    ->placeholder('—'),
                TextColumn::make('person')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cost')
                    ->money('GHS')
                    ->sortable()
                    ->summarize(Sum::make()->money('GHS')->label('Total')),
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
            ->defaultSort('expense_date', 'desc')
            ->striped();
    }

    public function exportExcel()
    {
        return (new ExpenseExport(
            dateFrom: $this->exportDateFrom,
            dateTo: $this->exportDateTo,
            mainCategoryId: $this->exportCategoryId,
            person: $this->exportPerson ?: null,
        ))->download('expense-report-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportPdf()
    {
        $expenses = $this->getFilteredExpenses();

        $pdf = Pdf::loadView('exports.expenses-pdf', [
            'expenses' => $expenses,
            'dateFrom' => $this->exportDateFrom,
            'dateTo' => $this->exportDateTo,
            'total' => $expenses->sum('cost'),
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'expense-report-' . now()->format('Y-m-d') . '.pdf'
        );
    }

    private function getFilteredExpenses()
    {
        return Expense::query()
            ->where('user_id', auth()->id())
            ->whereBetween('expense_date', [$this->exportDateFrom, $this->exportDateTo])
            ->when($this->exportCategoryId, fn ($q) => $q->where('main_category_id', $this->exportCategoryId))
            ->when($this->exportPerson, fn ($q) => $q->where('person', 'like', "%{$this->exportPerson}%"))
            ->with(['mainCategory', 'subCategory'])
            ->orderBy('expense_date', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.reports.reports', [
            'categories' => MainCategory::orderBy('name')->get(),
        ]);
    }
}
