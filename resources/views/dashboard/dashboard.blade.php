<x-app-layout>
    <x-title-header>
        {{ __('Dashboard') }}
    </x-title-header>

    <!-- Net Income, Savings, Expenses -->
    <div class="px-4 sm:px-6 lg:px-10 grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

        <!-- Net Income -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center space-x-4">
                <div class="bg-white/20 p-3 rounded-full">
                    <x-heroicon-o-banknotes class="w-8 h-8 text-white" />
                </div>
                <div>
                    <p class="text-sm font-medium uppercase tracking-wide">Net Income</p>
                    <p class="text-3xl font-bold">
                        {{ Auth::user()->currency_symbol }}
                        {{ floor($netIncome) != $netIncome ? number_format($netIncome, 2) : number_format($netIncome, 0) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Expenses -->
        <div class="bg-gradient-to-r from-pink-500 to-rose-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center space-x-4">
                <div class="bg-white/20 p-3 rounded-full">
                    <x-heroicon-o-credit-card class="w-8 h-8 text-white" />
                </div>
                <div>
                    <p class="text-sm font-medium uppercase tracking-wide">Total Expenses</p>
                    <p class="text-3xl font-bold">
                        {{ Auth::user()->currency_symbol }}
                        {{ floor($totalExpenses) != $totalExpenses ? number_format($totalExpenses, 2) : number_format($totalExpenses, 0) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Savings -->
        <div class="bg-gradient-to-r from-emerald-500 to-lime-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center space-x-4">
                <div class="bg-white/20 p-3 rounded-full">
                    <x-lucide-piggy-bank class="w-8 h-8 text-white" />
                </div>
                <div>
                    <p class="text-sm font-medium uppercase tracking-wide">Net Savings</p>
                    <p class="text-3xl font-bold">
                        {{ Auth::user()->currency_symbol }}
                        {{ floor($netSavings) != $netSavings ? number_format($netSavings, 2) : number_format($netSavings, 0) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graph Chart + Categories + Transactions -->
    <div class="px-4 sm:px-6 lg:px-10 grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">

        <!-- Chart -->
        <div class="flex flex-col w-full p-6 lg:col-span-2 bg-white shadow-md rounded-2xl">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Income, Expenses & Savings Overview</h2>
                <form method="GET" action="">
                    <select name="year_filter" id="year_filter_select"
                        class="h-10 w-40 border border-gray-300 shadow-sm rounded-lg px-4 text-sm text-gray-900 bg-white focus:ring-blue-500 focus:border-blue-500"
                        onchange="this.form.submit()">
                        <option value="">Select Year</option>
                        @for ($year = date('Y'); $year >= $oldestYear; $year--)
                            <option value="{{ $year }}" {{ request('year_filter') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </form>
            </div>

            <div x-data="LineChart()" x-init="fetchAndRenderChart()" class="w-full" style="height: 500px;">
                <canvas x-ref="lineChartCanvas"></canvas>
                <div x-show="!chart" class="flex items-center justify-center text-gray-400 h-full">
                    No data found.
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="flex flex-col w-full p-6 bg-white shadow-md rounded-2xl space-y-6">

            <!-- Earning -->
            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase mb-3">Top Earning Categories</h3>
                <div class="space-y-3">
                    @forelse ($top5Income as $topIncome)
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                @if ($topIncome->icon)
                                    <img src="{{ asset("storage/$topIncome->icon") }}"
                                        class="w-10 h-10 object-cover" />
                                @else
                                    <x-heroicon-o-photo class="w-8 h-8 text-gray-400" />
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium capitalize">{{ strtolower($topIncome->name) }}</p>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-blue-500 h-2 rounded-full"
                                        style="width: {{ ($topIncome->income_total / max($top5Income->pluck('income_total')->toArray())) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm font-bold">
                                {{ Auth::user()->currency_symbol }}{{ number_format($topIncome->income_total, 0) }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No Top Income Categories Found.</p>
                    @endforelse
                </div>
            </div>

            <!-- Spending -->
            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase mb-3">Top Spending Categories</h3>
                <div class="space-y-3">
                    @forelse ($top5Expenses as $topExpenses)
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                @if ($topExpenses->icon)
                                    <img src="{{ asset("storage/$topExpenses->icon") }}"
                                        class="w-10 h-10 object-cover" />
                                @else
                                    <x-heroicon-o-photo class="w-8 h-8 text-gray-400" />
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium capitalize">{{ strtolower($topExpenses->name) }}</p>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-rose-500 h-2 rounded-full"
                                        style="width: {{ ($topExpenses->expenses_total / max($top5Expenses->pluck('expenses_total')->toArray())) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm font-bold">
                                {{ Auth::user()->currency_symbol }}{{ number_format($topExpenses->expenses_total, 0) }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No Top Spending Categories Found.</p>
                    @endforelse
                </div>
            </div>

            <!-- Savings -->
            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase mb-3">Top Savings Categories</h3>
                <div class="space-y-3">
                    @forelse ($top5Savings as $topSavings)
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                @if ($topSavings->icon)
                                    <img src="{{ asset("storage/$topSavings->icon") }}"
                                        class="w-10 h-10 object-cover" />
                                @else
                                    <x-heroicon-o-photo class="w-8 h-8 text-gray-400" />
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium capitalize">{{ strtolower($topSavings->name) }}</p>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-emerald-500 h-2 rounded-full"
                                        style="width: {{ ($topSavings->savings_total / max($top5Savings->pluck('savings_total')->toArray())) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm font-bold">
                                {{ Auth::user()->currency_symbol }}{{ number_format($topSavings->savings_total, 0) }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No Top Savings Categories Found.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Transactions -->
        <div class="flex flex-col w-full p-6 bg-white shadow-md rounded-2xl">
            <div class="flex justify-between w-full mb-3 border-b border-gray-200 pb-2">
                <p class="text-gray-700 font-semibold">Latest Transactions</p>
                <a href="{{ route('allTransactions') }}" class="text-sm text-blue-600 hover:underline">
                    See all
                </a>
            </div>
            <div class="space-y-2">
                @forelse ($recentTransactions as $transaction)
                    @php
                        $badgeClass = match ($transaction->type) {
                            'income' => 'bg-blue-100 text-blue-600',
                            'expenses' => 'bg-rose-100 text-rose-600',
                            'savings' => 'bg-emerald-100 text-emerald-600',
                            default => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <div class="flex items-center space-x-3 pb-3 border-b border-gray-100">
                        <div
                            class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                            @if ($transaction->type === 'savings' && $transaction->savingsAccount->icon)
                                <img src="{{ asset('storage/' . $transaction->savingsAccount->icon) }}"
                                    class="w-10 h-10 object-cover" />
                            @elseif (($transaction->type === 'income' || $transaction->type === 'expenses') && $transaction->category->icon)
                                <img src="{{ asset('storage/' . $transaction->category->icon) }}"
                                    class="w-10 h-10 object-cover" />
                            @else
                                <x-heroicon-o-photo class="w-8 h-8 text-gray-400" />
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium capitalize">
                                {{ strtolower($transaction->type === 'income' || $transaction->type === 'expenses' ? $transaction->category->name : $transaction->savingsAccount->name) }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $transaction->date->format('M d, Y · h:i A') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p
                                class="text-sm font-bold {{ $transaction->type === 'expenses' ? 'text-rose-600' : ($transaction->type === 'income' ? 'text-blue-600' : 'text-emerald-600') }}">
                                {{ Auth::user()->currency_symbol }}{{ number_format($transaction->amount, 0) }}
                            </p>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $badgeClass }}">
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No recent transactions found.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    function LineChart() {
        return {
            chart: null,
            fetchAndRenderChart() {
                const params = new URLSearchParams({
                    year_filter: '{{ request('year_filter') }}',
                });

                fetch(`/line-chart?${params.toString()}`)
                    .then(res => res.json())
                    .then(data => {
                        const labels = data.labels;
                        const datasets = data.datasets.map(set => ({
                            label: set.label,
                            data: set.data,
                            backgroundColor: set.backgroundColor,
                            barPercentage: 1,
                            categoryPercentage: 1,
                            barThickness: 'flex',

                            borderRadius: {
                                topLeft: 10,
                                topRight: 10,
                                bottomLeft: 0,
                                bottomRight: 0
                            }
                        }));

                        const hasData = datasets.some(ds => ds.data.some(v => v > 0));
                        if (!hasData) {
                            this.chart = null;
                            return;
                        }

                        this.renderChart(labels, datasets);
                    });
            },
            renderChart(labels, datasets) {
                const ctx = this.$refs.lineChartCanvas.getContext('2d');
                if (this.chart) this.chart.destroy();

                this.chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets,
                        hoverOffset: 20
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        hover: {
                            mode: 'nearest',
                            intersect: true,
                            animationDuration: 400
                        },
                        interaction: {
                            mode: 'nearest',
                            intersect: true
                        },

                        animation: {
                            duration: 1000,
                            easing: 'easeInOutCubic'
                        },
                        plugins: {
                            datalabels: {
                                display: false
                            },
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 14,
                                        family: 'Poppins, sans-serif',
                                        weight: '600'
                                    },
                                    color: '#4B5563',
                                    boxWidth: 50,
                                    padding: 40
                                }

                            }
                        },
                        scales: {
                            x: {
                                barPercentage: 1,
                                categoryPercentage: 1,
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
    }
</script>
