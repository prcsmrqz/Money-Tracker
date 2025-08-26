<form method="POST" action="{{ route('transaction.store') }}">
    @csrf

    <!-- Time & Date -->
    <div class="flex flex-col space-y-1 mb-7">
        <p class="font-bold text-gray-600 dark:text-gray-400">TIME & DATE:</p>
        <input type="datetime-local" name="date" value="{{ old('date') }}"
            class="border border-gray-400 rounded-md px-2 py-2 dark:bg-gray-800 dark:text-white">
        @error('date', 'expensesForm')
            <div class="text-red-500 text-sm">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Amount -->
    <div class="flex flex-col space-y-1 mb-7">
        <p class="font-bold text-gray-600 dark:text-gray-400">AMOUNT:</p>
        <div class="relative">
            <div
                class="absolute inset-y-0 start-0 flex items-center ps-3.5 pe-3 border-e border-gray-300 dark:border-gray-600">
                <span class="text-gray-500 dark:text-gray-400">{{ Auth::user()->currency_symbol }}</span>
            </div>
            <input type="number" name="amount" value="{{ old('amount') }}"
                class="border border-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-14 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

        </div>
        @error('amount', 'expensesForm')
            <div class="text-red-500 text-sm">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="flex flex-col space-y-1 mb-7">
        <p class="font-bold text-gray-600 dark:text-gray-400">
            CATEGORY:
        </p>
        @php
            $selectedCategory = $expensesCategories->firstWhere('id', old('category_id'));
        @endphp

        <div x-data="{
            open: false,
            selected: {{ json_encode(
                $selectedCategory
                    ? [
                        'id' => $selectedCategory->id,
                        'name' => $selectedCategory->name,
                        'icon' => $selectedCategory->icon ? asset('storage/' . $selectedCategory->icon) : null,
                    ]
                    : null,
            ) }}
        }" class="relative">
            <button type="button" @click="open = !open"
                class="w-full border border-gray-400 rounded-md px-2 py-2 flex justify-between items-center dark:bg-gray-800 dark:text-white">
                <div class="flex items-center gap-2">
                    <template x-if="selected">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-200">
                                <template x-if="selected.icon">
                                    <img :src="selected.icon" alt="selected icon" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!selected.icon">
                                    <x-heroicon-o-photo class="w-6 h-6 text-black" />
                                </template>
                            </div>
                            <span x-text="selected.name"></span>
                        </div>
                    </template>
                    <template x-if="!selected">
                        <span>Select a category</span>
                    </template>
                </div>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" @click.away="open = false" @click.stop
                class="absolute z-10 mt-2 w-full bg-white dark:bg-gray-800 border border-gray-300 rounded-md shadow-lg">
                @foreach ($expensesCategories as $category)
                    <div @click.prevent.stop="selected = { id: '{{ $category->id }}', name: '{{ $category->name }}',
                        icon: {{ $category->icon ? '\'' . asset('storage/' . $category->icon) . '\'' : 'null' }} }; open = false"
                        class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer capitalize">
                        <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-200">
                            @if ($category->icon)
                                <img src="{{ asset('storage/' . $category->icon) }}" alt="icon"
                                    class="w-full h-full object-cover">
                            @else
                                <x-heroicon-o-photo class="w-6 h-6 text-black" />
                            @endif
                        </div>
                        <span>{{ strtolower($category->name) }}</span>
                    </div>
                @endforeach
            </div>

            <input type="hidden" name="category_id" :value="selected?.id">
            @error('category_id', 'expensesForm')
                <div class="text-red-500 text-sm">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>


    <div x-data="{ sourceType: '{{ old('source_type', 'income') }}' }" class="space-y-4 mb-5">
        <div class="flex flex-col gap-4 md:flex-row md:gap-5">

            <!-- SELECT SOURCE TYPE -->
            <div class="w-full md:flex-1">

                <p class="font-bold text-gray-600 dark:text-gray-400 mb-1">SOURCE:</p>
                <select class="w-full rounded-md border border-gray-400" name="source_type" x-model="sourceType">
                    <option value="income">Income</option>
                    <option value="savings">Savings</option>
                </select>
                @error('source_type', 'expensesForm')
                    <div class="text-red-500 text-sm">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- SOURCE INCOME -->
            <div class="w-full md:flex-1" x-show="sourceType === 'income'">
                <p class="font-bold text-gray-600 dark:text-gray-400 mb-1">SOURCE INCOME:</p>
                @php
                    $selectedSourceIncome = $incomeCategories->firstWhere('id', old('source_income'));
                @endphp

                <div x-data="{
                    open: false,
                    selected: {{ json_encode(
                        $selectedSourceIncome
                            ? [
                                'id' => $selectedSourceIncome->id,
                                'name' => $selectedSourceIncome->name,
                                'icon' => $selectedSourceIncome->icon ? asset('storage/' . $selectedSourceIncome->icon) : null,
                            ]
                            : null,
                    ) }}
                }" class="relative">
                    <button type="button" @click="open = !open"
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
                                <span>Select income category</span>
                            </template>
                        </div>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" @click.stop
                        class="absolute z-10 mt-2 w-full bg-white dark:bg-gray-800 border border-gray-300 rounded-md shadow-lg">
                        @foreach ($incomeCategories as $category)
                            <div @click.prevent.stop="selected = { id: '{{ $category->id }}', name: '{{ $category->name }}',
                            icon: {{ $category->icon ? '\'' . asset('storage/' . $category->icon) . '\'' : 'null' }} }; open = false"
                                class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer capitalize">
                                <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-200">
                                    @if ($category->icon)
                                        <img src="{{ asset('storage/' . $category->icon) }}" alt="icon"
                                            class="w-full h-full object-cover">
                                    @else
                                        <x-heroicon-o-photo class="w-6 h-6 text-black" />
                                    @endif
                                </div>
                                <span>{{ strtolower($category->name) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="source_income" :value="selected?.id">
                    @error('source_income', 'expensesForm')
                        <div class="text-red-500 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- SOURCE SAVINGS -->
            <div class="w-full md:flex-1" x-show="sourceType === 'savings'">
                <p class="font-bold text-gray-600 dark:text-gray-400 mb-1">SOURCE SAVINGS:</p>
                @php
                    $selectedSourceSavings = $savingsAccounts->firstWhere('id', old('source_savings'));
                @endphp

                <div x-data="{
                    open: false,
                    selected: {{ json_encode(
                        $selectedSourceSavings
                            ? [
                                'id' => $selectedSourceSavings->id,
                                'name' => $selectedSourceSavings->name,
                                'icon' => $selectedSourceSavings->icon ? asset('storage/' . $selectedSourceSavings->icon) : null,
                            ]
                            : null,
                    ) }}
                }" class="relative">
                    <button type="button" @click="open = !open"
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
                                <span>Select savings account</span>
                            </template>
                        </div>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" @click.stop
                        class="absolute z-10 mt-2 w-full bg-white dark:bg-gray-800 border border-gray-300 rounded-md shadow-lg">
                        @foreach ($savingsAccounts as $savings)
                            <div @click.prevent.stop="selected = { id: '{{ $savings->id }}', name: '{{ $savings->name }}',
                            icon: {{ $savings->icon ? '\'' . asset('storage/' . $savings->icon) . '\'' : 'null' }} }; open = false"
                                class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer capitalize">
                                <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-200">
                                    @if ($savings->icon)
                                        <img src="{{ asset('storage/' . $savings->icon) }}" alt="icon"
                                            class="w-full h-full object-cover">
                                    @else
                                        <x-heroicon-o-photo class="w-6 h-6 text-black" />
                                    @endif
                                </div>
                                <span>{{ strtolower($savings->name) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="source_savings" :value="selected?.id">
                    @error('source_savings', 'expensesForm')
                        <div class="text-red-500 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>



    <!-- Notes -->
    <div class="flex flex-col space-y-1 mb-10">
        <p class="font-bold text-gray-600 dark:text-gray-400">NOTES:</p>
        <textarea name="notes" value="{{ old('notes') }}"
            class="border border-gray-400 rounded-md px-2 py-2 dark:bg-gray-800 dark:text-white resize-none"></textarea>
        @error('notes', 'expensesForm')
            <div class="text-red-500 text-sm">
                {{ $message }}
            </div>
        @enderror
    </div>

    <input type="hidden" name="type" value="expenses">
    <!-- Submit -->
    <div class="flex flex-col space-y-1 w-full items-center justify-center pb-10">
        <span class="italic text-gray-600 text-sm mb-6 text-center">
            We’ll deduct this expenses based on your source.
        </span>
        <button type="submit"
            class="flex items-center justify-center px-6 py-3 w-full sm:w-1/2 md:w-1/3 bg-emerald-500 text-xl text-white font-bold rounded-md">
            <x-heroicon-s-check class="w-4 h-4 mr-1" />
            SAVE
        </button>
    </div>
</form>
