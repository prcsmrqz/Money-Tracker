<x-app-layout>
    <x-title-header>
        {{ __('Transaction') }}
    </x-title-header>

    <div class="px-4 sm:px-6 lg:px-10 mb-5">
        <div x-data="{ activeTab: '{{ $activeTab ?? 'income' }}' }">
            <x-transaction.transaction-tab />
            <x-transaction.transaction-tab-button :categories="$categories" :savingsAccounts="$savingsAccounts" :expensesCategories="$expensesCategories" />
        </div>
    </div>

</x-app-layout>
