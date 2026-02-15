<?php

namespace App\Livewire\Categories;

use App\Models\Expense;
use App\Models\MainCategory;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CategoryReport extends Component
{
    public MainCategory $mainCategory;

    public string $dateFrom = '';

    public string $dateTo = '';

    public function mount(MainCategory $mainCategory): void
    {
        $this->mainCategory = $mainCategory;
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatedDateFrom(): void
    {
        $this->dispatch('filters-updated');
    }

    public function updatedDateTo(): void
    {
        $this->dispatch('filters-updated');
    }

    public function getTotalExpensesProperty(): float
    {
        return $this->baseQuery()->sum('cost');
    }

    public function getExpenseCountProperty(): int
    {
        return $this->baseQuery()->count();
    }

    public function getAverageExpenseProperty(): float
    {
        $count = $this->expenseCount;

        return $count > 0 ? $this->totalExpenses / $count : 0;
    }

    public function getSubcategoryChartDataProperty(): array
    {
        $data = $this->baseQuery()
            ->select('sub_category_id', DB::raw('SUM(cost) as total'))
            ->groupBy('sub_category_id')
            ->get();

        $subCategories = SubCategory::whereIn('id', $data->pluck('sub_category_id')->filter())->get()->keyBy('id');

        $colors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1'];

        return [
            'labels' => $data->map(fn ($item) => $item->sub_category_id
                ? ($subCategories->get($item->sub_category_id)?->name ?? 'Unknown')
                : 'Uncategorized'
            )->values()->toArray(),
            'datasets' => [[
                'data' => $data->pluck('total')->map(fn ($v) => round($v, 2))->toArray(),
                'backgroundColor' => $data->values()->map(fn ($item, $i) => $colors[$i % count($colors)])->toArray(),
            ]],
        ];
    }

    public function getDailyTrendDataProperty(): array
    {
        $driver = DB::getDriverName();
        $dayExpr = match ($driver) {
            'sqlite' => DB::raw("strftime('%Y-%m-%d', expense_date) as day"),
            default => DB::raw("DATE_FORMAT(expense_date, '%Y-%m-%d') as day"),
        };

        $data = $this->baseQuery()
            ->select($dayExpr, DB::raw('SUM(cost) as total'))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return [
            'labels' => $data->pluck('day')->toArray(),
            'datasets' => [[
                'label' => 'Daily Spending',
                'data' => $data->pluck('total')->map(fn ($v) => round($v, 2))->toArray(),
                'backgroundColor' => $this->mainCategory->color ?? '#3B82F6',
                'borderColor' => $this->mainCategory->color ?? '#2563EB',
                'borderWidth' => 1,
            ]],
        ];
    }

    public function getExpensesProperty()
    {
        return $this->baseQuery()
            ->with('subCategory')
            ->orderByDesc('expense_date')
            ->get();
    }

    public function fetchChartData(): array
    {
        return [
            'subcategory' => $this->subcategoryChartData,
            'daily' => $this->dailyTrendData,
        ];
    }

    private function baseQuery()
    {
        return Expense::query()
            ->where('user_id', auth()->id())
            ->where('main_category_id', $this->mainCategory->id)
            ->whereBetween('expense_date', [$this->dateFrom, $this->dateTo]);
    }

    public function render()
    {
        return view('livewire.categories.category-report', [
            'subcategoryChart' => $this->subcategoryChartData,
            'dailyTrendChart' => $this->dailyTrendData,
        ])->title($this->mainCategory->name . ' — Report');
    }
}
