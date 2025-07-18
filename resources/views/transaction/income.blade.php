<x-app-layout>
    <x-title-header>
        {{ __('Transaction') }}
    </x-title-header>

    <div class="px-4 sm:px-6 lg:px-10">
        <div x-data="{ activeTab: 'income' }" class="w-full">
            <x-transaction-tab />
            <x-transaction-tab-button :categories="$categories" />
        </div>
    </div>

</x-app-layout>
