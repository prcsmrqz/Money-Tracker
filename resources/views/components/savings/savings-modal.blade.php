<div x-show="open" x-cloak x-transition @click.self="open = false; $dispatch('close-modal')"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div @click.stop
        class="relative bg-white dark:bg-gray-700 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg md:max-w-xl lg:max-w-4xl p-6">

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

        @php
            $hasCreateError = session('errors') && session('errors')->hasBag('create');
        @endphp

        <div class="mt-4 space-y-3">
            <form method="POST" action="{{ $action }}" enctype="multipart/form-data" x-data="{ previewUrl: null }">
                @csrf


                <div class="flex align-center justify-center mb-2">

                    <label for="icon"
                        class="cursor-pointer w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">

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
                            accept=".png, .jpg, .jpeg, .webp, .svg"
                            @change="
                            const file = $event.target.files[0];
                            const validTypes = ['image/png', 'image/jpg', 'image/jpeg', 'image/webp', 'image/svg+xml'];
                            const maxSize = 2 * 1024 * 1024; // 2MB in bytes

                            if (file) {
                                if (!validTypes.includes(file.type)) {
                                    Swal.fire('Error', 'Only PNG, JPG, JPEG, WEBP, and SVG files are allowed.', 'error');
                                    $event.target.value = '';
                                    previewUrl = null;
                                } else if (file.size > maxSize) {
                                    Swal.fire('Error', 'File size must be 2MB or less.', 'error');
                                    $event.target.value = '';
                                    previewUrl = null;
                                } else {
                                    previewUrl = URL.createObjectURL(file);
                                }
                            }
                        ">

                    </label>

                    @if ($errors->has('icon', 'create'))
                        <div class="text-red-500 text-sm mt-1 ml-2">
                            {{ $errors->first('icon') }}
                        </div>
                    @endif

                </div>

                <div class="flex flex-col space-y-1 mb-5">
                    <p class="font-medium text-black dark:text-gray-400">Savings Name:</p>
                    <input type="text" name="name" value="{{ $hasCreateError ? old('name') : '' }}"
                        class="border border-gray-400 rounded-md px-2 py-2 dark:bg-gray-800 dark:text-white">
                    @error('name', 'create')
                        <div class="text-red-500 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="flex flex-col space-y-1 mb-5">
                    <p class="font-medium text-black dark:text-gray-400">Type:
                        <em class="text-xs">(Debit, Credit, Investment, Cash, E-Wallet, etc.)</em>
                    </p>
                    <input type="text" name="type" value="{{ $hasCreateError ? old('type') : '' }}"
                        class="border border-gray-400 rounded-md px-2 py-2 dark:bg-gray-800 dark:text-white">
                    @error('type', 'create')
                        <div class="text-red-500 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="flex flex-col space-y-1 mb-5">
                    <p class="font-medium text-black dark:text-gray-400">Account Number:</p>
                    <input type="text" name="account_number"
                        value="{{ $hasCreateError ? old('account_number') : '' }}"
                        class="border border-gray-400 rounded-md px-2 py-2 dark:bg-gray-800 dark:text-white">
                    @error('account_number', 'create')
                        <div class="text-red-500 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="flex flex-col mb-5 w-full">
                    <div class="flex w-full space-x-4 items-start">
                        <!-- Time & Date -->
                        <div class="flex flex-col grow min-w-[300px]">
                            <label for="date" class="mb-1 font-medium text-black dark:text-gray-400">
                                Time & Date:
                            </label>
                            <input type="datetime-local" id="date" name="date"
                                value="{{ $hasCreateError ? old('date') : '' }}"
                                class="w-full border border-gray-400 rounded-md px-2 py-2 dark:bg-gray-800 dark:text-white">
                            @error('date', 'create')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Color Picker -->
                        <div class="flex flex-col w-20 shrink-0">
                            <label for="selectedColor"
                                class="mb-1 font-medium text-black dark:text-gray-400">Color:</label>
                            <div @click.stop>
                                <div id="color-picker" class="w-full rounded-md"></div>
                                <input type="hidden" name="color" id="selectedColor"
                                    value="{{ $hasCreateError ? old('color') : '#88E773' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col space-y-1 mb-5">
                    <p class="font-medium text-black dark:text-gray-400">
                        Amount:
                        <em class="text-xs">(Must not exceed to remaining income)</em>
                    </p>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pe-3 border-e border-gray-300 dark:border-gray-600">
                            <span class="text-gray-500 dark:text-gray-400">{{ Auth::user()->currency_symbol }}</span>
                        </div>
                        <input type="number" name="amount" value="{{ $hasCreateError ? old('amount') : '' }}"
                            class="border border-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-14 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                    </div>
                    @error('amount', 'create')
                        <div class="text-red-500 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <input type="hidden" name="transaction_type" value="{{ $type }}">

                <div class="flex flex-col space-y-1 w-full items-center justify-center pb-5">
                    <span class="italic text-gray-600 text-sm mb-3 text-center  mt-5">
                        We’ll move this amount from your remaining income.
                    </span>
                    <button type="submit"
                        class="flex items-center justify-center px-6 py-3 w-full sm:w-1/2 md:w-1/3 bg-emerald-500 text-xl text-white font-bold rounded-md">
                        <x-heroicon-s-check class="w-4 h-4 mr-1" />
                        SAVE
                    </button>
                </div>

            </form>

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
