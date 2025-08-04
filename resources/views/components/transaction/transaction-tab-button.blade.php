<div x-data="{ calculator: false }">
    <div class="bg-white dark:bg-gray-800 rounded-md shadow px-6 py-4 transition-all duration-300">
        <div class="flex justify-end mb-4">
            <button @click="calculator = !calculator" class="rounded-md p-2 flex items-center">
                <span :class="calculator ? 'text-blue-600' : 'text-gray-600'">
                    <x-heroicon-o-calculator class="w-8 h-8" />
                </span>
            </button>
        </div>

        <div class="flex flex-col lg:flex-row justify-between transition-all duration-300">

            <div :class="calculator ? 'lg:w-3/4' : 'w-full'" class="px-6 transition-all duration-300">
                <div x-show="activeTab === 'income'" x-cloak>
                    <x-transaction.transaction-income :categories="$categories" type="income" />
                </div>
                <div x-show="activeTab === 'expenses'" x-cloak></div>
                <div x-show="activeTab === 'savings'" x-cloak>
                    <x-transaction.transaction-income :savingsAccounts="$savingsAccounts" type="savings" />
                </div>
            </div>

            <x-transaction.calculator />

        </div>

    </div>
</div>
</div>
