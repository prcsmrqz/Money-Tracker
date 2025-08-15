@php
    $currentMode = request('mode') ?? 'icon';
@endphp

<ul class="flex text-sm font-medium text-center text-gray-600 rounded-lg dark:divide-gray-700 dark:text-gray-400">

    <!-- Icon Tab -->
    <li class="focus-within:z-10">
        <a href="{{ url()->current() }}?mode=icon"
            class="inline-block p-4 px-8 rounded-l-md flex items-center
           {{ $currentMode === 'icon'
               ? 'bg-gray-800 text-white dark:bg-gray-900 dark:text-white'
               : 'bg-white dark:bg-gray-800 text-gray-800 hover:text-gray-700 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-700' }}">
            <x-heroicon-s-chart-pie class="w-4 h-4 mr-2" />
            Icon
        </a>
    </li>

    <!-- Chart Tab -->
    <li class="focus-within:z-10">
        <a href="{{ url()->current() }}?mode=chart"
            class="inline-block p-4 px-8 flex items-center
           {{ $currentMode === 'chart'
               ? 'bg-gray-800 text-white dark:bg-gray-900 dark:text-white'
               : 'bg-white dark:bg-gray-800 text-gray-800 hover:text-gray-700 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-700' }}">
            <x-heroicon-s-view-columns class="w-4 h-4 mr-2" />
            Chart
        </a>
    </li>

    <!-- Table Tab -->
    <li class="focus-within:z-10">
        <a href="{{ url()->current() }}?mode=table"
            class="inline-block p-4 px-8 rounded-r-md flex items-center
           {{ $currentMode === 'table'
               ? 'bg-gray-800 text-white dark:bg-gray-900 dark:text-white'
               : 'bg-white dark:bg-gray-800 text-gray-800 hover:text-gray-700 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-700' }}">
            <x-heroicon-s-table-cells class="w-4 h-4 mr-2" />
            Table
        </a>
    </li>

</ul>
