<div class="flex flex-col items-center w-full">
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-5 w-full">
        @forelse ($categories as $category)
            <a href="{{ route('category.show', $category->id) }}"
                style="background-image: 
            radial-gradient(circle at left center, {{ $category->color }} 10%, transparent 90%),
            radial-gradient(circle at right center, {{ $category->color }} 10%, transparent 90%);"
                class="w-full rounded-xl shadow p-4 border border-gray-200 text-white 
                        transition-transform duration-200 ease-in-out transform 
                        hover:-translate-y-1 hover:scale-105 hover:shadow-lg">

                <div class="flex items-center gap-4">
                    @if ($category->icon)
                        <img src="{{ asset("storage/$category->icon") }}" alt="Icon"
                            class="w-12 h-12 rounded-full object-cover shadow-lg" />
                    @else
                        <div
                            class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center shadow-lg  cursor-pointer">
                            <x-heroicon-o-photo class="w-6 h-6 text-black" />
                        </div>
                    @endif

                    <div class="max-w-[6rem] sm:max-w-[8rem] ">
                        <label
                            class="block text-base truncate whitespace-nowrap overflow-hidden sm:text-lg font-medium  cursor-pointer">
                            {{ $category->name }}
                        </label>
                        <label class="block text-base sm:text-lg font-bold  cursor-pointer">
                            {{ Auth::user()->currency_symbol }}
                            {{ floor($category->totalIncome ?? 0) != ($category->totalIncome ?? 0)
                                ? number_format($category->totalIncome ?? 0, 2)
                                : number_format($category->totalIncome ?? 0) }}
                        </label>
                    </div>
                </div>
            </a>
        @empty
            <p>No categories available.</p>
        @endforelse
    </div>
</div>
