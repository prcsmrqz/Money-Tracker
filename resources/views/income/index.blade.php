<x-app-layout>
    <x-title-header>
        {{ __('Income') }}
    </x-title-header>

    <div class="px-4 sm:px-6 lg:px-10">
        <div x-data="{ open: {{ session()->get('errors') && session()->get('errors')->hasBag('default') ? 'true' : 'false' }} }">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2 mb-5">
                <button @click="open = true"
                    class="w-full sm:w-auto flex items-center justify-center bg-emerald-500 text-white text-base 
                            sm:text-lg font-bold py-2 px-4 rounded-md hover:bg-emerald-600 transition duration-200">
                    <x-heroicon-s-tag class="w-5 h-5 mr-2" />
                    List of Categories
                </button>
            </div>

            <x-category.category-modal title="Income Category" :storeAction="route('category.store')" updateAction="/category"
                :categories="$categories" :type="'income'" :open="true" />
        </div>

        <div x-data="{ activeTab: 'icon' }" class="w-full">
            <x-category.tab-buttons />
            <div class="mt-4 py-8 px-4 sm:px-6 lg:px-12 bg-white dark:bg-gray-800 rounded-md shadow-md w-full">
                <div x-show="activeTab === 'icon'" x-cloak>
                    <x-icon-tab.icons :categories="$categories" :type="'income'" />
                </div>
                <div x-show="activeTab === 'chart'" x-cloak>
                    <p class="text-gray-800 dark:text-gray-200 text-base sm:text-lg">hi</p>
                </div>
            </div>
            <div x-show="activeTab === 'icon'" class="px-4 sm:px-6 lg:px-10 mt-8">
                <div class="mt-8 rounded-md shadow-lg bg-white dark:bg-gray-800 p-6 py-10 text-center">
                    <p class="text-5xl font-bold text-gray-700 dark:text-gray-300">
                        <span>You earned</span>
                        {{ Auth::user()->currency_symbol }}{{ floor($totalIncome ?? 0) != ($totalIncome ?? 0) ? number_format($totalIncome ?? 0, 2) : number_format($totalIncome ?? 0) }}
                        <span>this month!</span>
                    </p>
                </div>
            </div>
        </div>

    </div>




</x-app-layout>
