<x-app-layout>
    <x-title-header>
        {{ __('Income') }}
    </x-title-header>

    <div class="px-10">
        <div class="flex items-center justify-end mb-5">
            <button
                class="flex items-center justify-center bg-emerald-500 text-white text-lg font-bold py-2 px-4 rounded-md hover:bg-emerald-600 transition duration-200">
                <x-heroicon-s-tag class="w-4 h-4 mr-2" />
                List of Categories
            </button>
        </div>

        <div x-data="{ activeTab: 'icon' }">
            <x-tab-buttons />
            <x-tab-data icon-message="This is the icons loaded from layout"
                chart-message="This is the charts loaded from layout" />
        </div>



</x-app-layout>
