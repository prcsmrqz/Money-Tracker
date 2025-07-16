<ul class="text-sm font-medium text-center text-gray-600 rounded-lg sm:flex dark:divide-gray-700 dark:text-gray-400">
    <li class="focus-within:z-10">
        <a href="#" @click.prevent="activeTab = 'icon'"
            :class="activeTab === 'icon'
                ?
                'text-gray-900 bg-gray-800 text-white dark:bg-gray-900 dark:text-white' :
                'bg-white dark:bg-gray-800 hover:text-gray-700 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-700'"
            class="inline-block p-4 px-8 rounded-l-md flex items-center">
            <x-heroicon-s-chart-pie class="w-4 h-4 mr-2" />
            Icon
        </a>
    </li>
    <li class="focus-within:z-10">
        <a href="#" @click.prevent="activeTab = 'chart'"
            :class="activeTab === 'chart'
                ?
                'text-gray-900 bg-gray-800 text-white dark:bg-gray-900 dark:text-white' :
                'bg-white dark:bg-gray-800 hover:text-gray-700 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-700'"
            class="inline-block p-4 px-8 rounded-r-md flex items-center">
            <x-heroicon-s-view-columns class="w-4 h-4 mr-2" />
            Chart
        </a>
    </li>
</ul>
