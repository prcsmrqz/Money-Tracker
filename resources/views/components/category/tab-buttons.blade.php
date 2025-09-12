@php
    $currentMode = request('mode') ?? 'icon';
@endphp

<ul class="flex text-[10px] sm:text-sm font-medium text-center text-gray-600 rounded-lg ">

    <!-- Icon Tab -->
    <li class="focus-within:z-10">
        <a href="{{ url()->current() }}?mode=icon"
            class="inline-block p-4 px-4 sm:px-8 rounded-l-md flex items-center
           {{ $currentMode === 'icon'
               ? 'bg-gray-800 text-white '
               : 'bg-white text-gray-800 hover:text-gray-700 hover:bg-gray-50 ' }}">
            <x-heroicon-s-chart-pie class="w-4 h-4 mr-2" />
            Icon
        </a>
    </li>

    <!-- Chart Tab -->
    <li class="focus-within:z-10">
        <a href="{{ url()->current() }}?mode=chart"
            class="inline-block p-4 px-4 sm:px-8 flex items-center
           {{ $currentMode === 'chart'
               ? 'bg-gray-800 text-white '
               : 'bg-white text-gray-800 hover:text-gray-700 hover:bg-gray-50 ' }}">
            <x-heroicon-s-view-columns class="w-4 h-4 mr-2" />
            Chart
        </a>
    </li>

    <!-- Table Tab -->
    <li class="focus-within:z-10">
        <a href="{{ url()->current() }}?mode=table"
            class="inline-block p-4 px-4 sm:px-8 rounded-r-md flex items-center
           {{ $currentMode === 'table'
               ? 'bg-gray-800 text-white'
               : 'bg-white text-gray-800 hover:text-gray-700 hover:bg-gray-50 ' }}">
            <x-heroicon-s-table-cells class="w-4 h-4 mr-2" />
            Table
        </a>
    </li>

</ul>
