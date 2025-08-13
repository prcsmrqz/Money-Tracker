<x-app-layout>
    <x-title-header>
        {{ __('Expenses') }}
    </x-title-header>

    <div class="px-4 sm:px-6 lg:px-10">
        <div x-data="{ open: {{ session()->get('errors') && session()->get('errors')->hasBag('default') ? 'true' : 'false' }} }">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2 mb-5 sm:mb-0">
                <button @click="open = true"
                    class="w-full sm:w-auto flex items-center justify-center bg-emerald-500 text-white text-base 
                            sm:text-lg font-bold py-2 px-4 rounded-md hover:bg-emerald-600 transition duration-200">
                    <x-heroicon-s-list-bullet class="w-5 h-5 mr-2" />
                    Manage Categories
                </button>
            </div>

            <x-category.category-modal title="Expenses Category List" :storeAction="route('category.store')" updateAction="/category"
                :categories="$categories" :type="'expenses'" :open="true" />
        </div>

        <div x-data="{ activeTab: '{{ $activeTab ?: 'icon' }}', chart: null }" class="w-full">

            <x-category.tab-buttons />

            <div class="mt-4 py-8 px-4 sm:px-6 lg:px-12 bg-white dark:bg-gray-800 rounded-md shadow-md w-full">
                <div x-show="activeTab === 'icon'" x-cloak>
                    <x-icon-tab.icons :categories="$categories" :type="'expenses'" />
                </div>

                <div x-show="activeTab === 'chart'" x-cloak>

                    <x-category.search-filter :oldestYear="$oldestYear" :search="false" />
                    <div x-data="chartComponent()" x-init="fetchAndRenderChart()" class="w-full" style="height: 500px;">
                        <div class="flex justify-center items-center h-full">
                            <canvas x-show="chart" x-ref="incomeChartCanvas" class="!w-full !h-full max-w-5xl"></canvas>
                            <div x-show="!chart" class="text-center text-gray-400 mt-4">
                                No data found.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Monthly summary --}}
            <div x-show="activeTab === 'icon'"
                class="grid grid-cols-2 md:grid-cols-3 gap-5 px-4 sm:px-6 lg:px-10 mt-5 mb-5">
                <div
                    class=" rounded-xl shadow-lg bg-white dark:bg-gray-800 p-4 md:p-5 lg:p-6 flex flex-col justify-center">
                    <p class="text-base md:text-lg lg:text-xl font-bold mb-3 text-green-600 dark:text-gray-300">
                        Monthly spent
                    </p>
                    <p class=" text-center text-3xl sm:text-6xl mb-5 font-bold text-black dark:text-gray-300">
                        {{ Auth::user()->currency_symbol }}
                        {{ floor($monthlySpent ?? 0) != ($monthlySpent ?? 0) ? number_format($monthlySpent ?? 0, 2) : number_format($monthlySpent ?? 0) }}
                    </p>
                    <p class="text-base md:text-lg lg:text-xl font-bold mb-3 text-red-600 dark:text-gray-300">
                        Overall spent
                    </p>
                    <p class=" text-center text-3xl sm:text-6xl mb-5 font-bold text-black dark:text-gray-300">
                        {{ Auth::user()->currency_symbol }}
                        {{ floor($totalSpent ?? 0) != ($totalSpent ?? 0) ? number_format($totalSpent ?? 0, 2) : number_format($totalSpent ?? 0) }}
                    </p>
                </div>
                <div class="rounded-xl shadow-lg bg-white dark:bg-gray-800 p-4 md:p-5 lg:p-6">
                    <p class="text-base md:text-lg lg:text-xl font-bold mb-3 text-black dark:text-gray-300">
                        Recent Transactions
                    </p>
                    <table class="table w-full text-sm sm:text-base text-left text-gray-800 dark:text-gray-200">
                        @forelse ($recentTransactions as $transaction)
                            <tr onClick="window.location.href='{{ route('category.show', $transaction->category->id) }}'"
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
                                        style="background-color: {{ $transaction->category->color }}2A; color: {{ $transaction->category->color }}">
                                        {{ $transaction->category->name }} </span>
                                </td>
                            </tr>
                        @empty
                            <div class="flex justify-center items-center h-32">
                                <p class="text-sm md:text-base italic text-gray-400">No transactions found.</p>
                            </div>
                        @endforelse
                    </table>
                </div>

                <div class="rounded-xl shadow-lg bg-white dark:bg-gray-800 p-4 md:p-5 lg:p-6">
                    <p class="text-base md:text-lg lg:text-xl font-bold mb-3 text-black dark:text-gray-300">
                        Top Spending Categories
                    </p>

                    @php
                        $max = $top5Expenses->sum('total');
                    @endphp

                    <ul>
                        @forelse ($top5Expenses as $expenses)
                            @php
                                $value = $expenses->total;
                                $percentage = $max > 0 ? ($value / $max) * 100 : 0;
                            @endphp
                            <li class="mb-5">
                                <span class="flex justify-between ">
                                    <strong
                                        class="font-bold capitalize truncate max-w-[50%] sm:max-w-[60%] md:max-w-none text-gray-800">
                                        {{ ucfirst(strtolower($expenses->name)) }}
                                    </strong>

                                    <span class="font-medium whitespace-nowrap text-gray-500 text-xs sm:text-sm">
                                        ₱{{ number_format($expenses->total, 2) }}
                                        <span class="hidden sm:inline"> spent of
                                            {{ floor($max) != $max ? number_format($max, 2) : number_format($max, 0) }}</span>
                                    </span>
                                </span>

                                <div class="w-full rounded-full h-2"
                                    style="background-color: {{ $expenses->color }}4A;">
                                    <div class="h-2 rounded-full"
                                        style="background-color: {{ $expenses->color }}; width: {{ $percentage }}%">
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

                    fetch(`/expenses-chart?${params.toString()}`)
                        .then(res => res.json())
                        .then(data => {
                            const ids = data.map(c => c.id);
                            const labels = data.map(c => c.name);
                            const expenses = data.map(c => c.totalExpenses);
                            const colors = data.map(c => c.color || '#3b82f6');

                            const hasData = expenses.length > 0 && expenses.some(v => v > 0);

                            if (!hasData) {
                                this.chart = null;
                                return;
                            }

                            this.renderChart(ids, labels, expenses, colors);
                        });
                },
                renderChart(ids, labels, data, colors) {
                    const ctx = this.$refs.incomeChartCanvas.getContext('2d');
                    if (this.chart) this.chart.destroy();

                    this.chart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Expenses',
                                data: data,
                                backgroundColor: colors,
                                hoverOffset: 40
                            }]
                        },
                        options: {
                            onClick: (event, elements) => {
                                if (elements.length > 0) {
                                    const index = elements[0].index;
                                    const categoryId = ids[index];

                                    const route = `/category/${categoryId}`;
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
                                        const shortLabel = label.length > 10 ? label.slice(0, 9) + '…' : label;
                                        return `${shortLabel}\n${percentage}%`;
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
                                    text: 'Expenses Overview',
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
</x-app-layout>
