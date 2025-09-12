<x-app-layout>
    <x-title-header>
        {{ __('All Transactions') }}
    </x-title-header>

    <div class="px-4 sm:px-6 lg:px-10">
        <div class="py-8 px-4 sm:px-6 lg:px-12 bg-white  rounded-md shadow-md w-full">
            <x-category.search-filter :oldestYear="$oldestYear" :search="true" :mode="'table'" />

            <table class="table w-full text-sm sm:text-base text-left text-gray-800 ">
                <thead class="text-center rounded-lg bg-gray-100 border-b border-gray-400 font-medium">
                    {{-- TIME --}}
                    <td class="hidden md:table-cell text-sm py-3 text-gray-600">
                        <a href="{{ request()->fullUrlWithQuery([
                            'sort' => 'date',
                            'order' => request('order') === 'asc' && request('sort') === 'date' ? 'desc' : 'asc',
                        ]) }}"
                            class="flex items-center justify-center gap-1">
                            TIME
                            @if (request('sort') === 'date')
                                {!! request('order') === 'asc' ? '▲' : '▼' !!}
                            @endif
                        </a>
                    </td>

                    {{-- AMOUNT --}}
                    <td class="text-xs lg:text-sm py-3 text-gray-600">
                        <a href="{{ request()->fullUrlWithQuery([
                            'sort' => 'amount',
                            'order' => request('order') === 'asc' && request('sort') === 'amount' ? 'desc' : 'asc',
                        ]) }}"
                            class="flex items-center justify-center gap-1">
                            AMOUNT
                            @if (request('sort') === 'amount')
                                {!! request('order') === 'asc' ? '▲' : '▼' !!}
                            @endif
                        </a>
                    </td>

                    {{-- CATEGORY (by category_name) --}}
                    <td class="text-xs lg:text-sm py-3 text-gray-600">
                        <a href="{{ request()->fullUrlWithQuery([
                            'sort' => 'name',
                            'order' => request('order') === 'asc' && request('sort') === 'name' ? 'desc' : 'asc',
                        ]) }}"
                            class="flex items-center justify-center gap-1">
                            CATEGORY
                            @if (request('sort') === 'name')
                                {!! request('order') === 'asc' ? '▲' : '▼' !!}
                            @endif
                        </a>
                    </td>

                    {{-- TYPE --}}
                    <td class="text-xs lg:text-sm py-3 text-gray-600">
                        <a href="{{ request()->fullUrlWithQuery([
                            'sort' => 'type',
                            'order' => request('order') === 'asc' && request('sort') === 'type' ? 'desc' : 'asc',
                        ]) }}"
                            class="flex items-center justify-center gap-1">
                            TYPE
                            @if (request('sort') === 'type')
                                {!! request('order') === 'asc' ? '▲' : '▼' !!}
                            @endif
                        </a>
                    </td>

                    {{-- NOTE --}}
                    <td class="hidden md:table-cell text-sm py-3 text-gray-600">
                        <a href="{{ request()->fullUrlWithQuery([
                            'sort' => 'notes',
                            'order' => request('order') === 'asc' && request('sort') === 'notes' ? 'desc' : 'asc',
                        ]) }}"
                            class="flex items-center justify-center gap-1">
                            NOTE
                            @if (request('sort') === 'notes')
                                {!! request('order') === 'asc' ? '▲' : '▼' !!}
                            @endif
                        </a>
                    </td>

                    {{-- ACTIONS (no sorting) --}}
                    <td class="text-xs lg:text-sm py-3 text-gray-600">
                        ACTIONS
                    </td>
                </thead>


                <tbody class=" text-center border-b border-gray-200 text-gray-500  ">

                    @forelse ($allTransactions as $transaction)
                        @php
                            $typeClass = match ($transaction->type) {
                                'income' => 'text-blue-600 bg-blue-100 ',
                                'expenses' => 'text-red-600 bg-red-100 ',
                                'savings' => 'text-emerald-600 bg-emerald-100 ',
                                default => '',
                            };
                        @endphp
                        <tr class="hover:bg-gray-200 :bg-gray-700 cursor-pointer">

                            <td class="hidden md:table-cell w-1/6 py-2 whitespace-nowrap text-xs lg:text-sm ">
                                {{ $transaction->date->format('F d, Y - h:i A') }}
                            </td>
                            <td class="w-1/6 py-2 whitespace-nowrap text-xs lg:text-sm">
                                {{ Auth::user()->currency_symbol }}
                                {{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="w-1/6 py-2 whitespace-nowrap text-xs lg:text-sm">
                                <span class="px-2 md:px-3 py-1 rounded-full font-medium"
                                    style="background-color: {{ $transaction->category->color }}2A; color: {{ $transaction->category->color }}">
                                    {{ ucfirst(strtolower($transaction->category->name)) }} </span>
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
                            <td
                                class="w-full px-4 py-2 space-x-2 whitespace-nowrap flex items-center justify-center mt-1 ">

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

                                    <x-transaction.modal :transaction="$transaction" :savingsAccounts="$allSavingsAccounts" :categories="$categories"
                                        :allCategories="$allCategories" />
                                </div>

                                <form x-data action="{{ route('transaction.destroy', $transaction->id) }}"
                                    method="POST"
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
                {{ $allTransactions->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</x-app-layout>
