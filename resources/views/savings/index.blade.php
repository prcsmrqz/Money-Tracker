<x-app-layout>
    <x-title-header>
        {{ __('Savings') }}
    </x-title-header>

    <div class="px-4 sm:px-6 lg:px-10">

        <div x-data="{ open: {{ session()->get('errors') && session()->get('errors')->hasBag('create') ? 'true' : 'false' }} }">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2 mb-5 sm:mb-0">
                <button @click="open = true"
                    class="w-full sm:w-auto flex items-center justify-center bg-emerald-500 text-white text-base 
                            sm:text-lg font-bold py-2 px-4 rounded-md hover:bg-emerald-600 transition duration-200">
                    <x-heroicon-s-plus class="w-5 h-5 mr-2" />
                    Add Savings Account
                </button>
            </div>

            <x-savings.savings-modal title="Add Savings Account" :action="route('savings.store')" type="savings" :open="true" />
        </div>


        <div x-data="{ activeTab: '{{ $activeTab ?: 'icon' }}', chart: null }" class="w-full">

            <x-category.tab-buttons />

            <div class="mt-4 py-8 px-4 sm:px-6 lg:px-12 bg-white dark:bg-gray-800 rounded-md shadow-md w-full">
                <div x-show="activeTab === 'icon'" x-cloak>
                    <x-icon-tab.savings-icons :savingsAccounts="$savingsAccounts" :type="'savings'" />
                </div>

                <div x-show="activeTab === 'chart'" x-cloak>

                    <x-category.search-filter :oldestYear="$oldestYear" :search="false" :mode="'chart'" />
                    <div x-data="chartComponent()" x-init="fetchAndRenderChart()" class="w-full" style="height: 500px;">
                        <div class="flex justify-center items-center h-full">
                            <canvas x-show="chart" x-ref="savingsChartCanvas"
                                class="!w-full !h-full max-w-5xl"></canvas>
                            <div x-show="!chart" class="text-center text-gray-400 mt-4">
                                No data found.
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div x-show="activeTab === 'icon'"
                class="grid grid-cols-2 md:grid-cols-3 gap-5 px-4 sm:px-6 lg:px-10 mt-5 mb-5">
                <div
                    class=" rounded-xl shadow-lg bg-white dark:bg-gray-800 p-4 md:p-5 lg:p-6 flex flex-col justify-center">
                    <p class="text-base md:text-lg lg:text-xl font-bold mb-3 text-green-600 dark:text-gray-300">
                        Monthly savings
                    </p>
                    <p class=" text-center text-3xl sm:text-6xl mb-5 font-bold text-black dark:text-gray-300">
                        {{ Auth::user()->currency_symbol }}
                        {{ floor($monthlySavings ?? 0) != ($monthlySavings ?? 0) ? number_format($monthlySavings ?? 0, 2) : number_format($monthlySavings ?? 0) }}
                    </p>
                    <p class="text-base md:text-lg lg:text-xl font-bold mb-3 text-red-600 dark:text-gray-300">
                        Overall savings
                    </p>
                    <p class=" text-center text-3xl sm:text-6xl mb-5 font-bold text-black dark:text-gray-300">
                        {{ Auth::user()->currency_symbol }}
                        {{ floor($totalSavings ?? 0) != ($totalSavings ?? 0) ? number_format($totalSavings ?? 0, 2) : number_format($totalSavings ?? 0) }}
                    </p>
                </div>
                <div class="rounded-xl shadow-lg bg-white dark:bg-gray-800 p-4 md:p-5 lg:p-6">
                    <p class="text-base md:text-lg lg:text-xl font-bold mb-3 text-black dark:text-gray-300">
                        Recent Transactions
                    </p>
                    <table class="table w-full text-sm sm:text-base text-left text-gray-800 dark:text-gray-200">
                        @forelse ($recentTransactions->take(5) as $transaction)
                            <tr onClick="window.location.href='{{ route('category.show', $transaction->savingsAccount->id) }}'"
                                class="border-b border-gray-200 text-center text-gray-500 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                <td class="hidden lg:table-cell w-1/6 py-3 whitespace-nowrap text-xs lg:text-sm">
                                    {{ $transaction->date->format('F d, Y') }}
                                </td>
                                <td class="w-1/6 py-3 whitespace-nowrap text-xs lg:text-sm">
                                    {{ Auth::user()->currency_symbol }}
                                    {{ number_format($transaction->amount, 2) }}
                                </td>
                                <td class="w-1/6 py-3 whitespace-nowrap text-xs lg:text-sm">
                                    <span class="px-3 py-1 rounded-full font-medium"
                                        style="background-color: {{ $transaction->savingsAccount->color }}2A; color: {{ $transaction->savingsAccount->color }}">
                                        {{ $transaction->savingsAccount->name }} </span>
                                </td>
                            </tr>
                        @empty
                            <p> No transactions found. </p>
                        @endforelse
                    </table>
                </div>

                <div class="rounded-xl shadow-lg bg-white dark:bg-gray-800 p-4 md:p-5 lg:p-6">
                    <p class="text-base md:text-lg lg:text-xl font-bold mb-3 text-black dark:text-gray-300">
                        Top Savings Categories
                    </p>

                    @php
                        $max = $top5Savings->sum('savings_total');
                    @endphp

                    <ul>
                        @forelse ($top5Savings as $savings)
                            @php
                                $value = $savings->savings_total;
                                $percentage = $max > 0 ? ($value / $max) * 100 : 0;
                            @endphp
                            <li class="mb-3">
                                <span class="flex justify-between ">
                                    <strong
                                        class="font-bold capitalize truncate max-w-[50%] sm:max-w-[60%] md:max-w-none text-gray-800">
                                        {{ ucfirst(strtolower($savings->name)) }}
                                    </strong>

                                    <span class="font-medium whitespace-nowrap text-gray-500 text-xs sm:text-sm">
                                        ₱{{ number_format($savings->savings_total, 2) }}
                                        <span class="hidden sm:inline"> spent of
                                            {{ floor($max) != $max ? number_format($max, 2) : number_format($max, 0) }}</span>
                                    </span>
                                </span>

                                <div class="w-full rounded-full h-2"
                                    style="background-color: {{ $savings->color }}4A;">
                                    <div class="h-2 rounded-full"
                                        style="background-color: {{ $savings->color }}; width: {{ $percentage }}%">
                                    </div>
                                </div>
                            </li>
                        @empty
                            <div class="flex justify-center items-center h-32">
                                <p class="text-sm md:text-base italic text-gray-400">No data found.</p>
                            </div>
                        @endforelse
                    </ul>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function chartComponent() {
        return {
            chart: null,
            fetchAndRenderChart() {
                const params = new URLSearchParams({
                    date_filter: '{{ request('date_filter') }}',
                    month_filter: '{{ request('month_filter') }}',
                    year_filter: '{{ request('year_filter') }}',
                    start: '{{ request('start') }}',
                    end: '{{ request('end') }}',
                });

                fetch(`/savings-chart?${params.toString()}`)
                    .then(res => res.json())
                    .then(data => {
                        const ids = data.map(c => c.id);
                        const labels = data.map(c => c.name);
                        const savings = data.map(c => c.totalSavings);
                        const colors = data.map(c => c.color || '#3b82f6');

                        const hasData = savings.length > 0 && savings.some(v => v > 0);

                        if (!hasData) {
                            this.chart = null;
                            return;
                        }

                        this.renderChart(ids, labels, savings, colors);
                    });
            },
            renderChart(ids, labels, data, colors) {
                const ctx = this.$refs.savingsChartCanvas.getContext('2d');
                if (this.chart) this.chart.destroy();

                this.chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Savings',
                            data: data,
                            backgroundColor: colors,
                            hoverOffset: 40
                        }]
                    },
                    options: {
                        onClick: (event, elements) => {
                            if (elements.length > 0) {
                                const index = elements[0].index;
                                const savingsId = ids[index];

                                const route = `/savings/${savingsId}`;
                                window.location.href = route;
                            }
                        },
                        onHover: (event, chartElement) => {
                            event.native.target.style.cursor = chartElement.length ? 'pointer' : 'default';
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1000,
                            easing: 'easeInOutCubic',
                            delay: (ctx) => 0,
                            loop: false,
                            animateRotate: true,
                            animateScale: true
                        },
                        interaction: {
                            mode: 'nearest',
                            intersect: true
                        },
                        plugins: {
                            datalabels: {
                                color: '#ffffff',
                                font: {
                                    weight: 'bold',
                                    size: 12
                                },
                                anchor: 'center',
                                align: 'center',
                                offset: 0,
                                clip: true,
                                formatter: (value, context) => {
                                    if (value === 0) return '';
                                    const label = context.chart.data.labels[context.dataIndex];
                                    const data = context.chart.data.datasets[0].data;
                                    const total = data.reduce((sum, val) => sum + val, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    const shortLabel = label.length > 10 ? label.slice(0, 9) + '...' :
                                        label;
                                    return `${shortLabel}\n${percentage}%`
                                }
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
                                    padding: 20
                                }
                            },
                            title: {
                                display: true,
                                text: 'Savings Overview',
                                align: 'center',
                                color: '#111827',
                                font: {
                                    size: 24,
                                    family: 'Poppins, sans-serif',
                                    weight: '700'
                                },
                                padding: {
                                    bottom: 20
                                }
                            }
                        }
                    }
                });
            }
        }
    }
</script>
