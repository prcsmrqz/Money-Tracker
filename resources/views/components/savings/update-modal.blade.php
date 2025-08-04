<div x-show="open" x-transition @click.self="open = false; $dispatch('close-modal')"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div @click.stop
        class="relative bg-white dark:bg-gray-700 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg md:max-w-xl lg:max-w-4xl p-6">

        <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-600 pb-3">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
            <a href="{{ route('savings.index') }}" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </a>
        </div>

        @php
            $hasError = session('errors') && session('errors')->hasBag('update_' . $savingsAccount->id);
        @endphp

        <div class="mt-4 space-y-3">

            <form method="POST" action="{{ $action }}" enctype="multipart/form-data" x-data="{
                previewUrlEdit: '{{ $hasError ? (old('icon') ? asset('storage/' . old('icon')) : null) : ($savingsAccount->icon ? asset('storage/' . $savingsAccount->icon) : null) }}'
            }">
                @csrf
                @method('PUT')

                <div class="flex align-center justify-center mb-2">
                    <label for="icon_edit_{{ $savingsAccount->id }}"
                        class="cursor-pointer w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">

                        <template x-if="previewUrlEdit">
                            <img :src="previewUrlEdit" alt="Preview" class="object-cover w-full h-full">
                        </template>
                        <template x-if="!previewUrlEdit">
                            @if ($savingsAccount->icon)
                                <img src="{{ asset('storage/' . $savingsAccount->icon) }}" alt="Current Icon"
                                    class="object-cover w-full h-full">
                            @else
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            @endif
                        </template>
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="file" id="icon_edit_{{ $savingsAccount->id }}" name="icon" class="hidden"
                            accept=".png, .jpg, .jpeg, .webp, .svg"
                            @change="
                            const file = $event.target.files[0];
                            const validTypes = ['image/png', 'image/jpg', 'image/jpeg', 'image/webp', 'image/svg+xml'];
                            const maxSize = 2 * 1024 * 1024; // 2MB in bytes

                            if (file) {
                                if (!validTypes.includes(file.type)) {
                                    Swal.fire('Error', 'Only PNG, JPG, JPEG, WEBP, and SVG files are allowed.', 'error');
                                    $event.target.value = '';
                                    previewUrlEdit = null;
                                } else if (file.size > maxSize) {
                                    Swal.fire('Error', 'File size must be 2MB or less.', 'error');
                                    $event.target.value = '';
                                    previewUrlEdit = null;
                                } else {
                                    previewUrlEdit = URL.createObjectURL(file);
                                }
                            } else {
                                // If no file is selected, revert to current icon or no icon
                                previewUrlEdit = '{{ $savingsAccount->icon ? asset('storage/' . $savingsAccount->icon) : null }}';
                            }
                        ">
                    </label>

                    @if ($errors->has('icon', 'update_' . $savingsAccount->id))
                        <div class="text-red-500 text-sm mt-1 ml-2">
                            {{ $errors->first('icon') }}
                        </div>
                    @endif
                </div>


                <div class="flex flex-col space-y-1 mb-5">
                    <p class="font-medium text-black dark:text-gray-400">Savings Name:</p>
                    <input type="text" name="name" value="{{ $hasError ? old('name') : $savingsAccount->name }}"
                        class="border border-gray-400 rounded-md px-2 py-2 dark:bg-gray-800 dark:text-white">

                    @error('name', 'update_' . $savingsAccount->id)
                        <div class="text-red-500 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="flex flex-col space-y-1 mb-5">
                    <p class="font-medium text-black dark:text-gray-400">Type:
                        <em class="text-xs">(Debit, Credit, Investment, Cash, E-Wallet, etc.)</em>
                    </p>
                    <input type="text" name="type" value="{{ $hasError ? old('type') : $savingsAccount->type }}"
                        class="border border-gray-400 rounded-md px-2 py-2 dark:bg-gray-800 dark:text-white">
                    @error('type', 'update_' . $savingsAccount->id)
                        <div class="text-red-500 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="flex flex-col space-y-1 mb-5">
                    <p class="font-medium text-black dark:text-gray-400">Account Number:</p>
                    <input type="text" name="account_number"
                        value="{{ $hasError ? old('account_number') : $savingsAccount->account_number }}"
                        class="border border-gray-400 rounded-md px-2 py-2 dark:bg-gray-800 dark:text-white">
                    @error('account_number', 'update_' . $savingsAccount->id)
                        <div class="text-red-500 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div x-data x-init="initPickr('selectedColor-{{ $savingsAccount->id }}', 'color-picker-{{ $savingsAccount->id }}', '{{ $hasError ? old('color') : $savingsAccount->color ?? '#88E773' }}')" class="flex flex-col w-20 shrink-0">
                    <label for="selectedColor-{{ $savingsAccount->id }}"
                        class="mb-1 font-medium text-black dark:text-gray-400">Color:</label>

                    <div @click.stop>
                        <div id="color-picker-{{ $savingsAccount->id }}" class="w-full rounded-md"></div>
                        <input type="hidden" name="color" id="selectedColor-{{ $savingsAccount->id }}"
                            value="{{ $hasError ? old('color') : $savingsAccount->color ?? '#88E773' }}
">
                    </div>
                </div>

                <div class="flex flex-col space-y-1 w-full items-center justify-center pb-5">
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
    function initPickr(inputId, pickerId, defaultColor) {
        const input = document.getElementById(inputId);
        const pickerEl = document.getElementById(pickerId);

        if (!input || !pickerEl) return;

        const pickr = Pickr.create({
            el: '#' + pickerId,
            theme: 'nano',
            default: defaultColor,
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
            input.value = color.toHEXA().toString();
            pickr.hide();
        });

        pickr.on('clear', () => {
            input.value = '';
        });
    }
</script>
