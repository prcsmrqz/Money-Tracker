<x-category.search-filter :oldestYear="$oldestYear" :search="true" :mode="'table'" />

<div class="overflow-x-auto">
    <table class="table-auto w-full text-sm sm:text-base text-left text-gray-800">
        <thead class="text-center rounded-lg bg-gray-100 border-b border-gray-400 font-medium">
            {{-- TIME --}}
            <td class="hidden md:table-cell text-sm py-3 text-gray-600 whitespace-nowrap">
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
            <td class="text-xs lg:text-sm py-3 text-gray-600 whitespace-nowrap">
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

            {{-- CATEGORY --}}
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
            <td class="hidden md:table-cell text-xs lg:text-sm py-3 text-gray-600 whitespace-nowrap">
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

            {{-- ACTIONS --}}
            <td class="text-xs lg:text-sm py-3 text-gray-600 whitespace-nowrap">
                ACTIONS
            </td>
        </thead>

        <tbody class="text-center border-b border-gray-200 text-gray-500">
            @forelse ($transactionsTable as $transaction)
                @php
                    $typeClass = match ($transaction->type) {
                        'income' => 'text-blue-600 bg-blue-100 ',
                        'expenses' => 'text-red-600 bg-red-100 ',
                        'savings' => 'text-emerald-600 bg-emerald-100',
                        default => '',
                    };

                    if ($transaction->type === 'savings') {
                        $color = $transaction->savingsAccount->color;
                        $name = $transaction->savingsAccount->name;
                    } else {
                        $color = $transaction->category->color;
                        $name = $transaction->category->name;
                    }
                @endphp

                <tr class="hover:bg-gray-200 cursor-pointer">
                    <td class="hidden md:table-cell py-2 text-xs lg:text-sm whitespace-nowrap">
                        {{ $transaction->date->format('F d, Y - h:i A') }}
                    </td>
                    <td class="py-2 text-xs lg:text-sm whitespace-nowrap">
                        {{ Auth::user()->currency_symbol }}
                        {{ number_format($transaction->amount, 2) }}
                    </td>
                    <td class="py-2 text-xs lg:text-sm">
                        <span class="px-2 md:px-3 py-1 rounded-full font-medium"
                            style="background-color: {{ $color }}2A; color: {{ $color }}">
                            {{ ucfirst(strtolower($name)) }}
                        </span>
                    </td>
                    <td class="hidden md:table-cell px-3 py-3 capitalize font-semibold whitespace-nowrap">
                        <span
                            class="{{ $typeClass }} px-2 md:px-3 py-1 rounded-full text-xs lg:text-sm inline-block">
                            {{ $transaction->type }}
                        </span>
                    </td>
                    <td class="hidden md:table-cell px-4 py-2 text-xs lg:text-sm break-words">
                        <div
                            class="line-clamp-2 break-words text-ellipsis {{ $transaction->notes ? '' : 'text-gray-400 italic' }}">
                            {{ $transaction->notes ?: 'No notes provided' }}
                        </div>
                    </td>
                    <td class="px-4 py-2 flex items-center justify-center gap-1 sm:gap-2 whitespace-nowrap">
                        <div x-data="{
                            open: {{ session('error_transaction_id') == $transaction->id ? 'true' : 'false' }},
                            edit: {{ session('error_transaction_id') == $transaction->id ? 'true' : 'false' }}
                        }" class="flex gap-1 sm:gap-2">
                            <button @click=" open = true; edit = false"
                                class="bg-blue-500 text-white p-1  sm:p-2 sm:px-3 rounded-md flex items-center justify-center hover:bg-blue-600 shadow-sm">
                                <x-heroicon-s-eye class="w-3 h-3" />
                            </button>
                            <button @click=" open = true; edit = true"
                                class="bg-orange-500 text-white p-1  sm:p-2 sm:px-3 rounded-md flex items-center justify-center hover:bg-orange-600 shadow-sm">
                                <x-heroicon-s-pencil-square class="w-3 h-3" />
                            </button>
                            <x-transaction.modal :transaction="$transaction" :savingsAccounts="$allSavingsAccounts" :categories="$categories"
                                :allCategories="$allCategories" />
                        </div>
                        <form x-data action="{{ route('transaction.destroy', $transaction->id) }}" method="POST"
                            @submit.prevent="confirmDelete($event, 'transaction', 'Delete this transaction?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 text-white  p-1 sm:p-2 sm:px-3 rounded-md flex items-center justify-center hover:bg-red-600 shadow-sm">
                                <x-heroicon-s-trash class="w-3 h-3" />
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-gray-400 italic py-3">No transactions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-5">
    {{ $transactionsTable->links('pagination::tailwind') }}
</div>
