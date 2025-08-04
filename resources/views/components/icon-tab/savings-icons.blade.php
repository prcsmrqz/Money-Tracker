<div class="flex flex-col items-center w-full">
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-5  gap-5 w-full">

        @forelse ($savingsAccounts as $savingsAccount)
            <div class="relative w-full group transition-all duration-200 ease-in-out">
                <div href=""
                    style="background-image: 
                            radial-gradient(circle at left center, {{ $savingsAccount->color }} 10%, transparent 85%),
                            radial-gradient(circle at right center, {{ $savingsAccount->color }} 10%, transparent 85%);"
                    class="block w-full rounded-xl shadow p-4 border border-gray-200 text-white cursor-pointer
                            transform transition-transform duration-200 ease-in-out 
                            group-hover:-translate-y-1 group-hover:scale-105 group-hover:shadow-lg">

                    <div class="flex items-start gap-4">
                        @if ($savingsAccount->icon)
                            <img src="{{ asset("storage/$savingsAccount->icon") }}" alt="Icon"
                                class="w-12 h-12 rounded-full object-cover shadow-lg" />
                        @else
                            <div
                                class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center shadow-lg cursor-pointer">
                                <x-heroicon-o-photo class="w-6 h-6 text-black" />
                            </div>
                        @endif

                        <div class="max-w-[6rem] sm:max-w-[8rem] min-w-0 overflow-hidden cursor-pointer">

                            <label
                                class="block truncate whitespace-nowrap overflow-hidden text-sm sm:text-xl font-medium leading-tight cursor-pointer">
                                {{ $savingsAccount->name }}
                            </label>

                            <span class="text-xs -mt-1 mb-1 block leading-tight cursor-pointer">
                                {{ $savingsAccount->type }}
                            </span>
                            <label
                                class="block truncate whitespace-nowrap overflow-hidden text-sm sm:text-xl font-medium leading-tight cursor-pointer">
                                {{ Auth::user()->currency_symbol }}
                                {{ floor($savingsAccount->totalSavings ?? 0) != ($savingsAccount->totaltotalSavingsIncome ?? 0)
                                    ? number_format($savingsAccount->totalSavings ?? 0, 2)
                                    : number_format($savingsAccount->totalSavings ?? 0) }}
                            </label>
                        </div>
                    </div>
                </div>

                <div x-data="{
                    openDropdown: false,
                    open: {{ session('errors') && session('errors')->hasBag('update_' . $savingsAccount->id) ? 'true' : 'false' }}
                }" class="absolute top-2 right-2 z-20 ">
                    <button @click="openDropdown = !openDropdown" @click.away="openDropdown = false"
                        class="p-1 transition-transform duration-200 ease-in-out group-hover:-translate-y-1 group-hover:scale-105">
                        <x-heroicon-s-ellipsis-vertical class="w-5 h-5 text-white hover:text-gray-900" />
                    </button>

                    <div x-show="openDropdown" x-transition
                        class="absolute -right-5 w-28 bg-white text-black rounded-md shadow text-sm z-50">
                        <button @click="open = true; openDropdown = false"
                            class="flex gap-2 w-full px-4 py-2 hover:bg-gray-200">
                            <x-heroicon-s-pencil-square class="w-4 text-orange-500" />
                            Edit
                        </button>

                        <form action="{{ route('savings.destroy', $savingsAccount->id) }}" method="POST"
                            @submit.prevent="confirmDelete($event, 'savings', 'Delete this savings account?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex gap-2 w-full text-left px-4 py-2 hover:bg-gray-200">
                                <x-heroicon-s-trash class="w-4 text-red-500" />
                                Delete
                            </button>
                        </form>
                    </div>

                    <x-savings.update-modal :savingsAccount='$savingsAccount' title="Edit Savings Account" :action="route('savings.update', $savingsAccount->id)"
                        type="savings" :open="true" />

                </div>
            </div>
        @empty
            <p> No savings available. </p>
        @endforelse
    </div>
</div>
