{{-- resources/views/components/category-modal.blade.php --}}
<div x-show="open" x-transition @click.self="open = false; $dispatch('close-modal')"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div @click.stop
        class="relative bg-white dark:bg-gray-700 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg md:max-w-xl lg:max-w-4xl p-6">

        <!-- Modal header -->
        <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-600 pb-3">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
            <button @click="open = false; $dispatch('close-modal')"
                class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>

        <div class="mt-4 space-y-3">
            <form method="POST" action="{{ $storeAction }}" enctype="multipart/form-data" x-data="{ previewUrl: null }">
                @csrf

                <div class="flex items-center space-x-3">

                    <!-- Circle Preview + File Input -->
                    <label for="icon"
                        class="cursor-pointer w-14 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">

                        <template x-if="previewUrl">
                            <img :src="previewUrl" alt="Preview" class="object-cover w-full h-full">
                        </template>
                        <template x-if="!previewUrl">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </template>
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="file" id="icon" name="icon" class="hidden"
                            @change="const file = $event.target.files[0]; if(file) previewUrl = URL.createObjectURL(file)">
                    </label>


                    <input type="text" id="name" name="name" placeholder="Category Name"
                        value="{{ old('name') }}"
                        class="rounded-md w-full p-2 border border-gray-300 focus:ring focus:ring-emerald-200 focus:outline-none">

                    <div @click.stop>
                        <div id="color-picker"></div>
                        <input type="hidden" name="color" id="selectedColor" value="{{ old('color', '#88E773') }}">

                    </div>

                    <button type="submit"
                        class="bg-emerald-500 flex items-center text-white px-4 py-2 rounded-md hover:bg-emerald-600 transition duration-200">
                        <x-heroicon-s-plus class="w-4 h-4 mr-2" />
                        Add
                    </button>
                </div>
            </form>


            <div class="text-red-500 text-sm mb-2 px-5">
                @if ($errors->has('icon'))
                    <div class="text-red-500 text-sm mt-1 ml-2">
                        {{ $errors->first('icon') }}
                    </div>
                @endif
                @if ($errors->has('name'))
                    <div class="text-red-500 text-sm ml-2">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>

            <div class="max-h-[500px] overflow-y-auto overflow-x-hidden space-y-2 px-3 sm:px-5">
                <x-category-list :categories="$categories" :action="$updateAction" :type="$type" />
            </div>


        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const selectedColorInput = document.getElementById('selectedColor');
        const pickr = Pickr.create({
            el: '#color-picker',
            theme: 'nano',
            default: selectedColorInput?.value || '#88E773',
            components: {
                preview: true,
                opacity: true,
                hue: true,
                interaction: {
                    input: true,
                    save: true,
                    clear: true
                }
            }
        });

        pickr.on('save', (color) => {
            document.getElementById('selectedColor').value = color.toHEXA().toString();
            pickr.hide();
        });
    });
</script>
