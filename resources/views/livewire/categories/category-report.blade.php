<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('categories.index') }}" wire:navigate class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            @if($mainCategory->color)
                <span class="w-4 h-4 rounded-full flex-shrink-0" style="background-color: {{ $mainCategory->color }}"></span>
            @endif
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $mainCategory->name }}</h2>
        </div>
        <div class="flex items-center gap-3">
            <input type="date" wire:model.live="dateFrom"
                   class="p-2 font-normal rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <span class="text-gray-500 dark:text-gray-400 text-sm">to</span>
            <input type="date" wire:model.live="dateTo"
                   class="p-2 font-normal rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
    </div>

    @if($mainCategory->description)
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ $mainCategory->description }}</p>
    @endif

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Spent</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">GHS {{ number_format($this->totalExpenses, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">Transactions</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->expenseCount }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">Avg per Expense</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">GHS {{ number_format($this->averageExpense, 2) }}</p>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Subcategory Breakdown --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">By Subcategory</h3>
            <div wire:ignore>
                <div id="subCatEmptyState" class="items-center justify-center h-64 text-gray-400" style="display: {{ empty($subcategoryChart['labels']) ? 'flex' : 'none' }}">
                    <p>No data for the selected period</p>
                </div>
                <canvas id="subCatChart" style="{{ empty($subcategoryChart['labels']) ? 'display:none' : '' }}"></canvas>
            </div>
        </div>

        {{-- Daily Trend --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Daily Trend</h3>
            <div wire:ignore>
                <div id="dailyEmptyState" class="items-center justify-center h-64 text-gray-400" style="display: {{ empty($dailyTrendChart['labels']) ? 'flex' : 'none' }}">
                    <p>No data for the selected period</p>
                </div>
                <canvas id="dailyTrendChart" style="{{ empty($dailyTrendChart['labels']) ? 'display:none' : '' }}"></canvas>
            </div>
        </div>
    </div>

    {{-- Expenses Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Expenses</h3>
        </div>

        @if($this->expenses->isEmpty())
            <div class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                No expenses found for this category in the selected date range.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Title</th>
                            <th class="px-6 py-3">Subcategory</th>
                            <th class="px-6 py-3">Person</th>
                            <th class="px-6 py-3 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($this->expenses as $expense)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-3 text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $expense->expense_date->format('M d, Y') }}</td>
                                <td class="px-6 py-3 text-gray-900 dark:text-white font-medium">{{ $expense->title }}</td>
                                <td class="px-6 py-3 text-gray-500 dark:text-gray-400">{{ $expense->subCategory?->name ?? '—' }}</td>
                                <td class="px-6 py-3 text-gray-500 dark:text-gray-400">{{ $expense->person }}</td>
                                <td class="px-6 py-3 text-right font-medium text-gray-900 dark:text-white whitespace-nowrap">GHS {{ number_format($expense->cost, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <td colspan="4" class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-white">Total</td>
                            <td class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">GHS {{ number_format($this->totalExpenses, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    @script
    <script>
        let subCatChart = null;
        let dailyChart = null;

        function renderCharts(subCatData, dailyData) {
            const subCatEl = document.getElementById('subCatChart');
            const subCatEmpty = document.getElementById('subCatEmptyState');
            const dailyEl = document.getElementById('dailyTrendChart');
            const dailyEmpty = document.getElementById('dailyEmptyState');

            // Subcategory Doughnut
            if (subCatEl) {
                if (subCatChart) subCatChart.destroy();
                subCatChart = null;
                if (subCatData.labels && subCatData.labels.length > 0) {
                    subCatEl.style.display = '';
                    if (subCatEmpty) subCatEmpty.style.display = 'none';
                    subCatChart = new Chart(subCatEl, {
                        type: 'doughnut',
                        data: subCatData,
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'bottom' }
                            }
                        }
                    });
                } else {
                    subCatEl.style.display = 'none';
                    if (subCatEmpty) subCatEmpty.style.display = 'flex';
                }
            }

            // Daily Trend Bar
            if (dailyEl) {
                if (dailyChart) dailyChart.destroy();
                dailyChart = null;
                if (dailyData.labels && dailyData.labels.length > 0) {
                    dailyEl.style.display = '';
                    if (dailyEmpty) dailyEmpty.style.display = 'none';
                    dailyChart = new Chart(dailyEl, {
                        type: 'bar',
                        data: dailyData,
                        options: {
                            responsive: true,
                            scales: {
                                y: { beginAtZero: true },
                                x: {
                                    ticks: {
                                        maxRotation: 45,
                                        callback: function(value, index) {
                                            const label = this.getLabelForValue(value);
                                            const d = new Date(label + 'T00:00:00');
                                            return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short' });
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: { display: false }
                            }
                        }
                    });
                } else {
                    dailyEl.style.display = 'none';
                    if (dailyEmpty) dailyEmpty.style.display = 'flex';
                }
            }
        }

        // Initial render with server-side data
        renderCharts(@js($subcategoryChart), @js($dailyTrendChart));

        // Re-fetch on filter change
        $wire.on('filters-updated', async () => {
            const data = await $wire.fetchChartData();
            renderCharts(data.subcategory, data.daily);
        });
    </script>
    @endscript
</div>
