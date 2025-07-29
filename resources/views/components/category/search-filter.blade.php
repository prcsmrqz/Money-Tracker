@props(['search' => false])
<div class="flex flex-wrap items-start gap-4 mb-5 px-5">
    <!-- Left Side: Filter + Date Range -->
    <div class="flex flex-col md:flex-row md:items-end gap-4 w-full md:w-auto">
        <a href="{{ url()->current() }}" class="bg-blue-600 p-2 px-5 rounded-md text-white">
            Clear
        </a>

        <!-- Filter Select -->
        <form method="GET" action="{{ url()->current() }}" class="w-full md:w-auto">
            @csrf
            <select name="date_filter" id="date_filter_select" onchange="handleDateFilterChange(this)"
                class="h-[42px] w-full sm:w-40 border border-gray-300 shadow-sm rounded px-5 pr-9 text-sm text-gray-900 bg-white focus:ring-blue-500 focus:border-blue-500">
                <option value="">All</option>
                <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days
                </option>
                <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}> Month & Year
                </option>
                <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom</option>
            </select>
        </form>
        <form method="GET" action="{{ url()->current() }}" class="w-full md:w-auto" id="select_month_form"
            style="display: none;">
            @csrf
            <select name="month_filter" id="month_filter_select" onchange="handleMonthYearSubmit()"
                class="h-[42px] w-full mr-2 sm:w-40 border border-gray-300 shadow-sm rounded px-5 pr-9 text-sm text-gray-900 bg-white focus:ring-blue-500 focus:border-blue-500">
                @php
                    $month = [
                        1 => 'January',
                        2 => 'February',
                        3 => 'March',
                        4 => 'April',
                        5 => 'May',
                        6 => 'June',
                        7 => 'July',
                        8 => 'August',
                        9 => 'September',
                        10 => 'October',
                        11 => 'November',
                        12 => 'December',
                    ];
                @endphp
                <option value="">Select Month</option>
                @foreach ($month as $key => $value)
                    <option value="{{ $key }}" {{ request('month_filter') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>

            <select name="year_filter" id="year_filter_select" onchange="handleMonthYearSubmit()"
                class="h-[42px] w-full sm:w-40 border border-gray-300 shadow-sm rounded px-5 pr-9 text-sm text-gray-900 bg-white focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Year</option>
                @for ($year = date('Y'); $year >= $oldestYear; $year--)
                    <option value="{{ $year }}" {{ request('year_filter') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </form>


        <form method="GET" action="{{ url()->current() }}" class="w-full md:w-auto" id="custom_date_form"
            style="display: none;">
            @csrf
            <div class="flex flex-row flex-wrap items-center gap-2 w-full">
                <!-- Start Date -->
                <div class="relative w-full sm:w-auto max-w-[180px]">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <x-heroicon-s-calendar-date-range class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                    </div>
                    <input type="date" id="datepicker-range-start" name="start" value="{{ request('start') }}"
                        onchange="this.form.submit()"
                        class="h-[42px] ps-10 pe-3 w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <span class="text-gray-500 dark:text-gray-300">to</span>

                <!-- End Date -->
                <div class="relative w-full sm:w-auto max-w-[180px]">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <x-heroicon-s-calendar-date-range class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                    </div>
                    <input type="date" id="datepicker-range-end" name="end" value="{{ request('end') }}"
                        onchange="this.form.submit()"
                        class="h-[42px] ps-10 pe-3 w-full text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <input type="hidden" name="date_filter" value="custom" />
            </div>
        </form>
    </div>

    <!-- Right Side: Search Input -->
    @if ($search)
        <div class="w-full md:w-1/4 ml-auto">
            <form method="GET" action="{{ request()->url() }}" class="w-full">
                <div class="flex items-center border border-gray-300 rounded-lg px-2 bg-white shadow-sm">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-500" />
                    <input type="text" name="filter[search]" value="{{ request('filter.search') }}"
                        placeholder="Search..."
                        class="ml-2 text-sm bg-transparent ring-0 focus:outline-none focus:ring-0 border-none w-full">
                </div>
            </form>
        </div>
    @endif
</div>

<script>
    function handleMonthYearSubmit() {
        const month = document.getElementById('month_filter_select').value;
        const year = document.getElementById('year_filter_select').value;
        if (month && year) {
            document.getElementById('select_month_form').submit();
        }
    }

    function handleDateFilterChange(select) {
        const customForm = document.getElementById('custom_date_form');
        const monthForm = document.getElementById('select_month_form');
        const monthSelect = document.getElementById('month_filter_select');
        const yearSelect = document.getElementById('year_filter_select');

        customForm.style.display = 'none';
        monthForm.style.display = 'none';

        if (select.value === 'custom') {
            customForm.style.display = 'flex';
        } else if (select.value === 'month') {
            monthForm.style.display = 'flex';
            if (monthSelect) monthSelect.selectedIndex = 0;
            if (yearSelect) yearSelect.selectedIndex = 0;
        } else {
            select.form.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const dateSelect = document.getElementById('date_filter_select');
        const customForm = document.getElementById('custom_date_form');
        const monthForm = document.getElementById('select_month_form');

        if (dateSelect && dateSelect.value === 'custom') {
            customForm.style.display = 'flex';
        }

        if (dateSelect && dateSelect.value === 'month') {
            monthForm.style.display = 'flex';
        }
    });
</script>
