<?php

namespace App\Livewire;

use App\Models\Expense;
use App\Models\MainCategory;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public string $dateFrom = '';
    public string $dateTo = '';

    public function mount(): void
    {
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

    public function getMonthlyAverageProperty(): float
    {
        $months = max(1, now()->parse($this->dateFrom)->diffInMonths(now()->parse($this->dateTo)) + 1);
        return $this->totalExpenses / $months;
    }

    public function getTopCategoryProperty(): ?string
    {
        $result = $this->baseQuery()
            ->select('main_category_id', DB::raw('SUM(cost) as total'))
            ->groupBy('main_category_id')
            ->orderByDesc('total')
            ->first();

        if (! $result || ! $result->main_category_id) {
            return null;
        }

        return MainCategory::find($result->main_category_id)?->name;
    }

    public function getCategoryChartDataProperty(): array
    {
        $data = $this->baseQuery()
            ->select('main_category_id', DB::raw('SUM(cost) as total'))
            ->groupBy('main_category_id')
            ->get();

        $categories = MainCategory::whereIn('id', $data->pluck('main_category_id'))->get()->keyBy('id');

        $colors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1'];

        return [
            'labels' => $data->map(fn ($item) => $categories->get($item->main_category_id)?->name ?? 'Uncategorized')->values()->toArray(),
            'datasets' => [[
                'data' => $data->pluck('total')->map(fn ($v) => round($v, 2))->toArray(),
                'backgroundColor' => $data->values()->map(fn ($item, $i) => $categories->get($item->main_category_id)?->color ?? ($colors[$i % count($colors)]))->toArray(),
            ]],
        ];
    }

    public function getMonthlyTrendDataProperty(): array
    {
        $driver = DB::getDriverName();
        $monthExpr = match ($driver) {
            'sqlite' => DB::raw("strftime('%Y-%m', expense_date) as month"),
            default => DB::raw("DATE_FORMAT(expense_date, '%Y-%m') as month"),
        };

        $data = Expense::query()
            ->where('user_id', auth()->id())
            ->select($monthExpr, DB::raw('SUM(cost) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        return [
            'labels' => $data->pluck('month')->toArray(),
            'datasets' => [[
                'label' => 'Monthly Spending',
                'data' => $data->pluck('total')->map(fn ($v) => round($v, 2))->toArray(),
                'backgroundColor' => '#3B82F6',
                'borderColor' => '#2563EB',
                'borderWidth' => 1,
            ]],
        ];
    }

    private function baseQuery()
    {
        return Expense::query()
            ->where('user_id', auth()->id())
            ->whereBetween('expense_date', [$this->dateFrom, $this->dateTo]);
    }

    public function fetchChartData(): array
    {
        return [
            'category' => $this->categoryChartData,
            'monthly' => $this->monthlyTrendData,
        ];
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'categoryChart' => $this->categoryChartData,
            'monthlyTrendChart' => $this->monthlyTrendData,
        ]);
    }
}
