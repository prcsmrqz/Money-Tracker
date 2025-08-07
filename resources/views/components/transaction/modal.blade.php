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
