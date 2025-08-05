<x-app-layout>
    <x-title-header>
        {{ __('Savings') }}
    </x-title-header>

    <div class="px-4 sm:px-6 lg:px-10">

        <div x-data="{ open: {{ session()->get('errors') && session()->get('errors')->hasBag('create') ? 'true' : 'false' }} }">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2 mb-5">
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

                    <x-category.search-filter :oldestYear="$oldestYear" :search="false" />
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


            <div x-show="activeTab === 'icon'" class="px-4 sm:px-6 lg:px-10 mt-5">
                <div class="mt-8 rounded-md shadow-md bg-white dark:bg-gray-800 p-4 py-6 lg:p-6 lg:py-10 text-center">
                    <p class="text-xl sm:text-5xl font-bold text-gray-700 dark:text-gray-300">
                        <span>Total Saved</span>
                        {{ Auth::user()->currency_symbol }}
                        {{ floor($totalIncome ?? 0) != ($totalIncome ?? 0) ? number_format($totalIncome ?? 0, 2) : number_format($totalIncome ?? 0) }}
                        <span>this month!</span>
                    </p>
                </div>
            </div>

            <div x-show="activeTab === 'icon'" class="grid grid-cols-2 gap-5 px-4 sm:px-6 lg:px-10 mt-5 mb-10">
                <div
                    class="rounded-md shadow-lg bg-white dark:bg-gray-800 p-4 py-6 lg:p-6 lg:py-8 flex flex-col items-center justify-center text-center">
                    <p class="text-lg sm:text-2xl font-bold mb-3 text-gray-700">Net Savings</p>
                    <p class="text-3xl sm:text-6xl font-bold text-black dark:text-gray-300">
                        {{ Auth::user()->currency_symbol }}
                        {{ floor($totalSavings ?? 0) != ($totalSavings ?? 0) ? number_format($totalSavings ?? 0, 2) : number_format($totalSavings ?? 0) }}
                    </p>
                </div>

                <div class="rounded-md shadow-lg bg-white dark:bg-gray-800 p-4 py-6 lg:p-6 lg:py-6">
                    <p class="text-base sm:text-3xl text-start font-bold mb-3 text-gray-700 dark:text-gray-300">
                        Most Funded Savings:
                    </p>

                    <ol class="px-1 sm:px-14 font-bold text-sm sm:text-3xl space-y-2">
                        @forelse ($top3Savings as $savings)
                            <li class="grid grid-cols-2 items-center gap-2">
                                <span class="flex items-center gap-1 overflow-hidden">
                                    <span>{{ $loop->iteration }}.</span>
                                    <strong
                                        class="truncate block max-w-[130px] sm:max-w-full overflow-hidden text-ellipsis">
                                        {{ $savings->name }}
                                    </strong>
                                </span>
                                <span class="text-right whitespace-nowrap">
                                    ₱{{ number_format($savings->totalSavings, 2) }}
                                </span>
                            </li>
                        @empty
                            <p class="text-xs sm:text-base">No data found.</p>
                        @endforelse
                    </ol>
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
