<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpenseExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    public function __construct(
        protected string $dateFrom,
        protected string $dateTo,
        protected ?int $mainCategoryId = null,
        protected ?string $person = null,
    ) {}

    public function query()
    {
        $query = Expense::query()
            ->where('user_id', auth()->id())
            ->with(['mainCategory', 'subCategory'])
            ->whereBetween('expense_date', [$this->dateFrom, $this->dateTo]);

        if ($this->mainCategoryId) {
            $query->where('main_category_id', $this->mainCategoryId);
        }
        if ($this->person) {
            $query->where('person', 'like', "%{$this->person}%");
        }

        return $query->orderBy('expense_date', 'desc');
    }

    public function headings(): array
    {
        return ['Date', 'Title', 'Description', 'Category', 'Subcategory', 'Person', 'Amount (GHS)'];
    }

    public function map($expense): array
    {
        return [
            $expense->expense_date->format('Y-m-d'),
            $expense->title,
            $expense->description,
            $expense->mainCategory?->name ?? 'Uncategorized',
            $expense->subCategory?->name ?? '—',
            $expense->person,
            $expense->cost,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
