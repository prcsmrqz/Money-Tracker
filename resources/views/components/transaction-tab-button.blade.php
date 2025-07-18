<div class="flex px-8 pt-4 justify-end bg-white">
    <button class="bg-gray-200 rounded-md p-2 flex items-center"> <x-heroicon-o-calculator
            class="w-10 h-10 hover:text-blue-600" />
    </button>
</div>
<div class="px-40 bg-white dark:bg-gray-800 rounded-b-md shadow-b w-full">

    <div x-show="activeTab === 'income'" x-cloak>

        <form method="POST" action="{{ route('transaction.store') }}">
            @csrf

            <div class="flex flex-col space-y-1 mb-7">
                <p class="font-bold text-gray-600 dark:text-gray-400">TIME & DATE:</p>
                <input type="datetime-local" name="datetime"
                    class="border border-gray-400 rounded-md px-2 py-2 dark:bg-gray-800 dark:text-white">
            </div>

            <div class="flex flex-col space-y-1 mb-7">
                <p class="font-bold text-gray-600 dark:text-gray-400">AMOUNT:</p>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 start-0 flex items-center ps-3.5 pe-3 border-e border-gray-300 dark:border-gray-600">
                        <span class="text-gray-500 dark:text-gray-400">{{ Auth::user()->currency_symbol }}</span>
                    </div>
                    <input type="text" name="amount"
                        class="border border-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-14 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>


            </div>


            <div class="flex flex-col space-y-1 mb-7">
                <p class="font-bold text-gray-600 dark:text-gray-400">CATEGORY:</p>
                <div x-data="{ open: false, selected: null }" class="relative">
                    <button type="button" @click="open = !open"
                        class="w-full border border-gray-400 rounded-md px-2 py-2 flex justify-between items-center dark:bg-gray-800 dark:text-white">
                        <div class="flex items-center gap-2">
                            <template x-if="selected">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-200">
                                        <img :src="selected.icon" alt="selected icon"
                                            class="w-full h-full object-cover">
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
                        @foreach ($categories as $category)
                            <div @click.prevent.stop="
                                selected = {
                                    id: '{{ $category->id }}',
                                    name: '{{ $category->name }}',
                                    icon: '{{ asset('storage/' . $category->icon) }}'
                                }; 
                                open = false"
                                class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-200">
                                    <img src="{{ asset('storage/' . $category->icon) }}" alt="icon"
                                        class="w-full h-full object-cover">
                                </div>
                                <span>{{ $category->name }}</span>
                            </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="category_id" :value="selected?.id">
                </div>
            </div>


            <div class="flex flex-col space-y-1 mb-20">
                <p class="font-bold text-gray-600 dark:text-gray-400">NOTES:</p>
                <textarea type="text" name="notes" class="border border-gray-400 rounded-md px-2 dark:bg-gray-800 dark:text-white"></textarea>
            </div>

            <div class="flex flex-col space-y-1 mt-auto w-full items-center justify-center pb-10">
                <span class="italic text-gray-600 text-sm mb-6">
                    We’ll move this income to your income money.
                </span>
                <button type="submit"
                    class="flex items-center justify-center p-3 w-1/4 bg-emerald-500 text-xl text-white font-bold rounded-md ">
                    <x-heroicon-s-check class="w-4 h-4 mr-1" />
                    SAVE
                </button>
            </div>
        </form>
    </div>
    <div x-show="activeTab === 'expenses'" x-cloak>
    </div>
    <div x-show="activeTab === 'savings'" x-cloak>
    </div>
</div>
