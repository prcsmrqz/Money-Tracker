<div class="flex flex-col items-center w-full">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-5  gap-5 w-full">
        @forelse ($categories as $category)
            <a href="{{ route('category.show', $category->id) }}"
                style="background-image: 
                    radial-gradient(circle at left center, {{ $category->color }} 10%, transparent 85%),
                    radial-gradient(circle at right center, {{ $category->color }} 10%, transparent 85%);"
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

                    <div class="max-w-[6rem] sm:max-w-[8rem] min-w-0 overflow-hidden cursor-pointer">
                        <label
                            class="block truncate whitespace-nowrap capitalize overflow-hidden text-sm sm:text-xl font-medium leading-tight cursor-pointer">
                            {{ strtolower($category->name) }}
                        </label>
                        <label
                            class="block truncate whitespace-nowrap overflow-hidden text-sm sm:text-xl font-medium leading-tight cursor-pointer">
                            {{ Auth::user()->currency_symbol }}
                            {{ floor($category->total ?? 0) != ($category->total ?? 0)
                                ? number_format($category->total ?? 0, 2)
                                : number_format($category->total ?? 0) }}
                        </label>
                    </div>
                </div>

            </a>
        @empty
            <div class="col-span-full flex justify-center items-center mt-5">
                <p class="text-gray-500 text-sm italic">
                    No categories found.
                </p>
            </div>
        @endforelse
    </div>
</div>

<div class="mt-5">
    {{ $categories->links('pagination::tailwind') }}
</div>
