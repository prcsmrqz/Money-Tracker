<div x-show="open" x-transition @click.self="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 sm:p-6">

    <div @click.stop class="relative w-full max-w-xl rounded-lg bg-white dark:bg-gray-700 shadow-lg p-4 sm:p-6">
        <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-600 pb-3">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Transaction
            </h3>
            <button @click="open = false" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('transaction.update', $transaction->id) }}">
            @csrf
            @method('PUT')

            <div class="mt-4 mb-6">
                <label class="block font-bold text-gray-600 dark:text-gray-400 mb-1">TIME & DATE:</label>
                <input type="datetime-local" name="date" :disabled="!edit"
                    value="{{ $transaction->date->format('Y-m-d\TH:i') }}"
                    class="w-full border border-gray-400 text-black rounded-md px-2 py-2 dark:bg-gray-800 dark:text-white" />
                @error('date', 'update')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block font-bold text-gray-600 dark:text-gray-400 mb-1">AMOUNT:</label>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 start-0 flex items-center ps-3.5 pe-3 border-e border-gray-300 dark:border-gray-600">
                        <span class="text-gray-500 dark:text-gray-400">{{ Auth::user()->currency_symbol }}</span>
                    </div>
                    <input type="text" name="amount" :disabled="!edit"
                        value="{{ old('amount', $transaction->amount) }}"
                        class="w-full ps-14 p-2.5 border border-gray-400 text-black rounded-lg dark:bg-gray-700 dark:text-white" />
                </div>
                @error('amount', 'update')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>

            @if ($transaction->type == 'expenses' && $savingsAccounts)
                <div class="flex flex-col space-y-1 mb-7">
                    <p class="font-bold text-gray-600 dark:text-gray-400">
                        SOURCE:
                    </p>

                    @php
                        $defaultSourceTypeId = old('source_type', $transaction->source_type ?? null);
                        $defaultSelected = null;

                        if ($defaultSourceTypeId == 0) {
                            $defaultSelected = ['id' => 0, 'name' => 'REMAINING INCOME', 'icon' => null];
                        } else {
                            $selectedSavings = $savingsAccounts->firstWhere('id', $defaultSourceTypeId);
                            if ($selectedSavings) {
                                $defaultSelected = [
                                    'id' => $selectedSavings->id,
                                    'name' => $selectedSavings->name,
                                    'icon' => $selectedSavings->icon
                                        ? asset('storage/' . $selectedSavings->icon)
                                        : null,
                                ];
                            }
                        }
                    @endphp

                    <div x-data="{ open: false, selected: @js($defaultSelected) }" class="relative">
                        <button type="button" @click="open = !open" :disabled="!edit"
                            class="w-full border border-gray-400 rounded-md px-2 py-2 flex justify-between items-center dark:bg-gray-800 dark:text-white">
                            <div class="flex items-center gap-2">
                                <template x-if="selected">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-200">
                                            <template x-if="selected.icon">
                                                <img :src="selected.icon" alt="selected icon"
                                                    class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!selected.icon">
                                                <x-heroicon-o-photo class="w-6 h-6 text-black" />
                                            </template>
                                        </div>
                                        <span x-text="selected.name"></span>
                                    </div>
                                </template>
                                <template x-if="!selected">
                                    <span>Select a source type</span>
                                </template>
                            </div>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" @click.stop
                            class="absolute z-10 mt-2 w-full bg-white dark:bg-gray-800 border border-gray-300 rounded-md shadow-lg">
                            <div @click.prevent.stop="selected = { id: 0, name: 'REMAINING INCOME', icon: null }; open = false"
                                class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                <div
                                    class="w-6 h-6 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                    <x-heroicon-o-photo class="w-6 h-6 text-black" />
                                </div>
                                <span>REMAINING INCOME</span>
                            </div>

                            @foreach ($savingsAccounts as $savings)
                                <div @click.prevent.stop="selected = {
                            id: {{ $savings->id }},
                            name: '{{ addslashes($savings->name) }}',
                            icon: {{ $savings->icon ? '\'' . asset('storage/' . $savings->icon) . '\'' : 'null' }}
                        }; open = false"
                                    class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                    <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-200">
                                        @if ($savings->icon)
                                            <img src="{{ asset('storage/' . $savings->icon) }}" alt="icon"
                                                class="w-full h-full object-cover">
                                        @else
                                            <x-heroicon-o-photo class="w-6 h-6 text-black" />
                                        @endif
                                    </div>
                                    <span>{{ $savings->name }}</span>
                                </div>
                            @endforeach
                        </div>

                        <input type="hidden" name="source_type" :value="selected?.id ?? ''">
                        @error('source_type', 'expensesForm')
                            <div class="text-red-500 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            @endif


            <div class="mb-6">
                <label class="block font-bold text-gray-600 dark:text-gray-400 mb-1">NOTES:</label>
                <textarea name="notes" :disabled="!edit" rows="3"
                    class="w-full border border-gray-400 text-black rounded-md px-2 py-2 dark:bg-gray-800 dark:text-white">{{ old('notes', $transaction->notes) }}</textarea>
                @error('notes', 'update')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" name="category_id" value="{{ $transaction->category_id }}">
            <input type="hidden" name="savings_account_id" value="{{ $transaction->savings_account_id }}">
            <input type="hidden" name="type" value="{{ $transaction->type }}">

            <template x-if="edit">
                <div class="flex flex-col items-center mt-6">
                    <span class="italic text-gray-600 text-sm mb-4 text-center">
                        We’ll move this income to your income money.
                    </span>
                    <button type="submit"
                        class="flex items-center justify-center px-6 py-3 bg-emerald-500 text-white text-xl font-bold rounded-md">
                        <x-heroicon-s-check class="w-4 h-4 mr-1" />
                        SAVE
                    </button>
                </div>
            </template>
        </form>
    </div>
</div>
