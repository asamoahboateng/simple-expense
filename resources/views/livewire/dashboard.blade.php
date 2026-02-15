<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h2>
        <div class="flex items-center gap-3">
            <input type="date" wire:model.live="dateFrom"
                   class="p-2 font-normal rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <span class="text-gray-500 dark:text-gray-400 text-sm">to</span>
            <input type="date" wire:model.live="dateTo"
                   class="p-2 font-normal rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Expenses</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">GHS {{ number_format($this->totalExpenses, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Monthly Average</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">GHS {{ number_format($this->monthlyAverage, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Top Category</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->topCategory ?? 'N/A' }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Expense Count</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->expenseCount }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Category Pie Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Spending by Category</h3>
            <div wire:ignore>
                <div id="categoryEmptyState" class="items-center justify-center h-64 text-gray-400" style="display: {{ empty($categoryChart['labels']) ? 'flex' : 'none' }}">
                    <p>No data for the selected period</p>
                </div>
                <canvas id="categoryPieChart" style="{{ empty($categoryChart['labels']) ? 'display:none' : '' }}"></canvas>
            </div>
        </div>

        {{-- Monthly Trend Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Monthly Spending Trend</h3>
            <div wire:ignore>
                <div id="trendEmptyState" class="items-center justify-center h-64 text-gray-400" style="display: {{ empty($monthlyTrendChart['labels']) ? 'flex' : 'none' }}">
                    <p>No data available</p>
                </div>
                <canvas id="monthlyTrendChart" style="{{ empty($monthlyTrendChart['labels']) ? 'display:none' : '' }}"></canvas>
            </div>
        </div>
    </div>

    @script
    <script>
        let categoryChart = null;
        let trendChart = null;

        function renderCharts(categoryData, trendData) {
            const categoryEl = document.getElementById('categoryPieChart');
            const categoryEmpty = document.getElementById('categoryEmptyState');
            const trendEl = document.getElementById('monthlyTrendChart');
            const trendEmpty = document.getElementById('trendEmptyState');

            // Category Pie Chart
            if (categoryEl) {
                if (categoryChart) categoryChart.destroy();
                categoryChart = null;
                if (categoryData.labels && categoryData.labels.length > 0) {
                    categoryEl.style.display = '';
                    if (categoryEmpty) categoryEmpty.style.display = 'none';
                    categoryChart = new Chart(categoryEl, {
                        type: 'doughnut',
                        data: categoryData,
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'bottom' }
                            }
                        }
                    });
                } else {
                    categoryEl.style.display = 'none';
                    if (categoryEmpty) categoryEmpty.style.display = 'flex';
                }
            }

            // Monthly Trend Chart
            if (trendEl) {
                if (trendChart) trendChart.destroy();
                trendChart = null;
                if (trendData.labels && trendData.labels.length > 0) {
                    trendEl.style.display = '';
                    if (trendEmpty) trendEmpty.style.display = 'none';
                    trendChart = new Chart(trendEl, {
                        type: 'bar',
                        data: trendData,
                        options: {
                            responsive: true,
                            scales: {
                                y: { beginAtZero: true }
                            },
                            plugins: {
                                legend: { display: false }
                            }
                        }
                    });
                } else {
                    trendEl.style.display = 'none';
                    if (trendEmpty) trendEmpty.style.display = 'flex';
                }
            }
        }

        // Initial render with server-side data
        renderCharts(@js($categoryChart), @js($monthlyTrendChart));

        // Re-fetch on filter change
        $wire.on('filters-updated', async () => {
            const data = await $wire.fetchChartData();
            renderCharts(data.category, data.monthly);
        });
    </script>
    @endscript
</div>
