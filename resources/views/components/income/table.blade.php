<x-category.search-filter :oldestYear="$oldestYear" :search="true" :mode="'table'" />

<table class="table w-full text-sm sm:text-base text-left text-gray-800 dark:text-gray-200">
    <thead class="text-center rounded-lg bg-gray-100 border-b border-gray-400 font-medium">
        <td class="hidden md:table-cell text-sm py-3 text-gray-600">
            TIME
        </td>
        <td class="text-xs lg:text-sm py-3 text-gray-600">
            AMOUNT
        </td>
        <td class="text-xs lg:text-sm py-3 text-gray-600">
            CATEGORY
        </td>
        <td class="text-xs lg:text-sm py-3 text-gray-600">
            TYPE
        </td>

        <td class="hidden md:table-cell text-sm py-3 text-gray-600">
            NOTE
        </td>
        <td class="text-xs lg:text-sm py-3 text-gray-600">
            ACTIONS
        </td>
    </thead>

    <tbody
        class=" text-center border-b border-gray-200 text-gray-500 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">

        @forelse ($transactionsTable as $transaction)
            @php
                $typeClass = match ($transaction->type) {
                    'income' => 'text-blue-600 bg-blue-100 dark:bg-blue-700',
                    'expenses' => 'text-red-600 bg-red-100 dark:bg-red-700',
                    'savings' => 'text-emerald-600 bg-emerald-100 dark:bg-emerald-700',
                    default => '',
                };
            @endphp
            <tr>

                <td class="hidden md:table-cell w-1/6 py-2 whitespace-nowrap text-xs lg:text-sm ">
                    {{ $transaction->date->format('F d, Y - h:i A') }}
                </td>
                <td class="w-1/6 py-2 whitespace-nowrap text-xs lg:text-sm">
                    {{ Auth::user()->currency_symbol }}
                    {{ number_format($transaction->amount, 2) }}
                </td>
                <td class="w-1/6 py-2 whitespace-nowrap text-xs lg:text-sm">
                    <span class="px-3 py-1 rounded-full font-medium"
                        style="background-color: {{ $transaction->category->color }}2A; color: {{ $transaction->category->color }}">
                        {{ $transaction->category->name }} </span>
                </td>
                <td class="w-1/6 px-3 py-3 capitalize font-semibold">
                    <span
                        class="{{ $typeClass }} px-2 md:px-3 py-1 rounded-full text-sm inline-block text-xs lg:text-sm">
                        {{ $transaction->type }}
                    </span>
                </td>
                <td class="hidden md:table-cell w-2/6 px-4 py-2">
                    <div
                        class="line-clamp-2 break-words text-ellipsis text-xs lg:text-sm {{ $transaction->notes ? '' : 'text-gray-400 italic' }}">
                        {{ $transaction->notes ?: 'No notes provided' }}
                    </div>
                </td>
                <td class="w-full px-4 py-2 space-x-2 whitespace-nowrap flex items-center justify-center mt-1 ">

                    <div x-data="{
                        open: {{ session('error_transaction_id') == $transaction->id ? 'true' : 'false' }},
                        edit: {{ session('error_transaction_id') == $transaction->id ? 'true' : 'false' }}
                    }" class="flex gap-2 text-left">
                        <button @click=" open = true; edit = false"
                            class="bg-blue-500 text-white p-1 md:p-2 px-2 md:px-3 rounded-md flex items-center justify-center hover:bg-blue-600 shadow-sm">
                            <x-heroicon-s-eye class="w-4 h-4 " />
                        </button>
                        <button @click=" open = true; edit = true"
                            class="bg-orange-500 text-white p-1 md:p-2 px-2 md:px-3 rounded-md flex items-center justify-center hover:bg-orange-600 shadow-sm">
                            <x-heroicon-s-pencil-square class="w-4 h-4" />
                        </button>

                        <x-transaction.modal :transaction="$transaction" :savingsAccounts="$savingsAccounts" :categories="$categories" :allCategories="$allCategories" />
                    </div>

                    <form x-data action="{{ route('transaction.destroy', $transaction->id) }}" method="POST"
                        @submit.prevent="confirmDelete($event, 'transaction', 'Delete this transaction?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 text-white p-1 md:p-2 px-2 md:px-3 rounded-md flex items-center justify-center hover:bg-red-600 shadow-sm">
                            <x-heroicon-s-trash class="w-4 h-4" />
                        </button>
                    </form>
                </td>


            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-gray-400 italic py-3"> No transactions found.</td>
            </tr>
        @endforelse

    </tbody>


</table>
<div class="mt-5">
    {{-- Pagination links --}}
    {{ $transactionsTable->links('pagination::tailwind') }}
</div>
