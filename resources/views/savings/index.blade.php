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


        <div x-data="{ activeTab: 'icon' }" class="w-full">

            <x-category.tab-buttons />

            <div class="mt-4 py-8 px-4 sm:px-6 lg:px-12 bg-white dark:bg-gray-800 rounded-md shadow-md w-full">
                <div x-show="activeTab === 'icon'" x-cloak>
                    <x-icon-tab.savings-icons :savingsAccounts="$savingsAccounts" :type="'savings'" />
                </div>

                <div x-show="activeTab === 'chart'" x-cloak>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
