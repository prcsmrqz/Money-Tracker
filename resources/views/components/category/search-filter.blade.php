@props(['search' => false])

<form method="GET" action="{{ url()->current() }}" class="flex flex-wrap items-start gap-4 mb-5 px-5">
    {{-- Date Filter Dropdown --}}
    <select name="date_filter" id="date_filter_select" onchange="handleDateFilterChange(this)"
        class="h-[42px] w-full sm:w-40 border border-gray-300 shadow-sm rounded px-5 pr-9 text-sm text-gray-900 bg-white focus:ring-blue-500 focus:border-blue-500">
        <option value="">All</option>
        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
        <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
        <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days
        </option>
        <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>Month & Year</option>
        <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom</option>
    </select>

    {{-- Month & Year Filter --}}
    <div class="flex gap-2" id="month_year_filter" style="display: none;">
        <select name="month_filter" id="month_filter_select"
            class="h-[42px] w-full sm:w-40 border border-gray-300 shadow-sm rounded px-5 pr-9 text-sm text-gray-900 bg-white focus:ring-blue-500 focus:border-blue-500">
            <option value="">Select Month</option>
            @foreach ([1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'] as $key => $value)
                <option value="{{ $key }}" {{ request('month_filter') == $key ? 'selected' : '' }}>
                    {{ $value }}</option>
            @endforeach
        </select>

        <select name="year_filter" id="year_filter_select"
            class="h-[42px] w-full sm:w-40 border border-gray-300 shadow-sm rounded px-5 pr-9 text-sm text-gray-900 bg-white focus:ring-blue-500 focus:border-blue-500">
            <option value="">Select Year</option>
            @for ($year = date('Y'); $year >= $oldestYear; $year--)
                <option value="{{ $year }}" {{ request('year_filter') == $year ? 'selected' : '' }}>
                    {{ $year }}</option>
            @endfor
        </select>
    </div>

    {{-- Custom Date Range Filter --}}
    <div class="flex gap-2" id="custom_date_filter" style="display: none;">
        <input type="date" name="start" value="{{ request('start') }}"
            class="h-[42px] w-full sm:w-40 border border-gray-300 shadow-sm rounded px-3 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500" />
        <span class="text-gray-500 self-center">to</span>
        <input type="date" name="end" value="{{ request('end') }}"
            class="h-[42px] w-full sm:w-40 border border-gray-300 shadow-sm rounded px-3 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500" />
    </div>

    {{-- Search Input --}}
    @if ($search)
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end flex-grow gap-2 sm:gap-3">

            <div class="w-full sm:w-60 mb-2 sm:mb-0">
                <div class="flex items-center border border-gray-300 rounded-lg px-2 bg-white shadow-sm">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-500" />
                    <input type="text" name="filter[search]" value="{{ request('filter.search') }}"
                        placeholder="Search..."
                        class="ml-2 text-sm bg-transparent h-[38px] ring-0 focus:outline-none focus:ring-0 border-none w-full" />
                </div>
            </div>

            <button type="submit"
                class="bg-blue-600 font-medium px-5 rounded-md h-[38px] text-white hover:bg-blue-700">Apply</button>

            <a href="{{ url()->current() }}"
                class="bg-gray-300 font-medium text-gray-800 px-4 rounded-md h-[38px] flex items-center justify-center hover:bg-gray-400">Clear</a>

        </div>
    @else
        <div class="grid grid-cols-2 sm:auto-cols-max sm:flex gap-2 w-full sm:w-auto">
            <button type="submit"
                class="bg-blue-600 font-medium rounded-md h-[38px] px-5 text-white w-full  hover:bg-blue-700">Apply</button>

            <a href="{{ url()->current() }}"
                class="bg-gray-300 font-medium text-gray-800 rounded-md h-[38px] px-5 flex items-center justify-center w-full hover:bg-gray-400">Clear</a>
        </div>
    @endif
</form>

<script>
    function handleDateFilterChange(select) {
        const monthFilter = document.getElementById('month_year_filter');
        const customFilter = document.getElementById('custom_date_filter');

        const monthSelect = document.getElementById('month_filter_select');
        const yearSelect = document.getElementById('year_filter_select');
        const startInput = document.querySelector('input[name="start"]');
        const endInput = document.querySelector('input[name="end"]');

        // Hide and disable both filter blocks to prevent append
        monthFilter.style.display = 'none';
        customFilter.style.display = 'none';

        monthSelect.disabled = true;
        yearSelect.disabled = true;
        startInput.disabled = true;
        endInput.disabled = true;

        if (select.value === 'month') {
            monthFilter.style.display = 'flex';
            monthSelect.disabled = false;
            yearSelect.disabled = false;
        } else if (select.value === 'custom') {
            customFilter.style.display = 'flex';
            startInput.disabled = false;
            endInput.disabled = false;
        }

        // Clear all date fields when "All" is selected
        if (select.value === '') {
            monthSelect.value = '';
            yearSelect.value = '';
            startInput.value = '';
            endInput.value = '';
        }
    }

    // Run on page load
    window.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('date_filter_select');
        if (select) handleDateFilterChange(select);
    });
</script>
