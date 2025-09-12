<ul class="flex flex-col sm:flex-row w-full text-lg font-medium text-gray-600 rounded-lg border-b border-gray-200 ">
    <li class="w-full focus-within:z-10">
        <a href="#" @click.prevent="activeTab = 'income'"
            :class="activeTab === 'income'
                ?
                'text-gray-900 bg-gray-800 text-white  ' :
                'bg-white  hover:text-gray-700 hover:bg-gray-50   border-r border-gray-300 '"
            class="block w-full p-4 rounded-tl-md flex items-center justify-center text-center">
            <x-heroicon-s-arrow-down-tray class="w-4 h-4 mr-2" />
            Income
        </a>
    </li>
    <li class="w-full focus-within:z-10">
        <a href="#" @click.prevent="activeTab = 'expenses'"
            :class="activeTab === 'expenses'
                ?
                'text-gray-900 bg-gray-800 text-white  ' :
                'bg-white  hover:text-gray-700 hover:bg-gray-50  border-r border-gray-300 '"
            class="block w-full p-4 flex items-center justify-center text-center">
            <x-heroicon-s-arrow-up-tray class="w-4 h-4 mr-2" />
            Expenses
        </a>
    </li>
    <li class="w-full focus-within:z-10">
        <a href="#" @click.prevent="activeTab = 'savings'"
            :class="activeTab === 'savings'
                ?
                'text-gray-900 bg-gray-800 text-white  ' :
                'bg-white  hover:text-gray-700 hover:bg-gray-50 '"
            class="block w-full p-4 rounded-tr-md flex items-center justify-center text-center">
            <x-heroicon-s-banknotes class="w-4 h-4 mr-2" />
            Savings
        </a>
    </li>
</ul>
