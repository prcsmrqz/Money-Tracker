@forelse ($categories as $category)
    <div x-data="{
        isEditing: false,
        previewUrlEdit: '{{ $category->icon ? asset("storage/$category->icon") : '' }}',
        originalPreviewUrlEdit: '{{ $category->icon ? asset("storage/$category->icon") : '' }}',
        action: '{{ $action }}',
        formId: {{ $category->id }},
        pickrInstance: null,
        resetEdit() {
            this.isEditing = false;
            this.previewUrlEdit = '{{ $category->icon ? asset("storage/$category->icon") : '' }}';
            this.$refs.fileInput.value = null;
            if (this.pickrInstance) this.pickrInstance.disable();
        },
        formAction() {
            return `${this.action}/${this.formId}`;
        },
        init() {
            this.pickrInstance = Pickr.create({
                el: '#color-picker-{{ $category->id }}',
                theme: 'nano',
                default: '{{ old('color_' . $category->id, $category->color) ?? '#42445A' }}',
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
    
            this.pickrInstance.on('save', (color) => {
                document.getElementById('selectedColor{{ $category->id }}').value = color.toHEXA().toString();
                this.pickrInstance.hide();
            });
    
            this.$watch('isEditing', (val) => {
                if (this.pickrInstance) {
                    if (val) {
                        this.pickrInstance.enable();
                    } else {
                        this.pickrInstance.disable();
                    }
                }
            });
    
            // Start disabled
            this.$nextTick(() => {
                if (this.pickrInstance) this.pickrInstance.disable();
            });
        }
    }" x-init="init()" @custom-close-modal.window="resetEdit()"
        class="w-full border-b border-gray-200 p rounded-lg dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-900 dark:text-white">

        <div class="flex items-center justify-between w-full gap-2 flex-wrap sm:flex-nowrap">
            <!-- Edit Form -->
            <form :action="formAction()" method="POST" enctype="multipart/form-data"
                class="flex flex-1 items-center gap-2 flex-wrap sm:flex-nowrap">
                @csrf
                @method('PATCH')

                <!-- File Upload -->
                <label for="iconEdit{{ $category->id }}" :class="isEditing ? '' : 'pointer-events-none'"
                    class="cursor-pointer w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden transition shrink-0">
                    <template x-if="previewUrlEdit">
                        <img :src="previewUrlEdit" alt="Preview" class="object-cover w-full h-full" />
                    </template>
                    <template x-if="!previewUrlEdit">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </template>

                    <input type="file" id="iconEdit{{ $category->id }}" name="iconEdit_{{ $category->id }}"
                        accept=".png, .jpg, .jpeg, .webp, .svg" x-ref="fileInput" class="hidden"
                        @change="
                        if (isEditing) {
                            const file = $event.target.files[0];
                            const validTypes = ['image/png', 'image/jpg', 'image/jpeg', 'image/webp', 'image/svg+xml'];
                            const maxSize = 2 * 1024 * 1024;

                            if (file) {
                                if (!validTypes.includes(file.type)) {
                                    Swal.fire('Error', 'Only PNG, JPG, JPEG, WEBP, and SVG files are allowed.', 'error');
                                    $event.target.value = '';
                                    previewUrlEdit = originalPreviewUrlEdit;
                                } else if (file.size > maxSize) {
                                    Swal.fire('Error', 'File size must be 2MB or less.', 'error');
                                    $event.target.value = '';
                                    previewUrlEdit = originalPreviewUrlEdit;
                                } else {
                                    previewUrlEdit = URL.createObjectURL(file);
                                }
                            }
                        }
                    ">

                </label>

                <input type="hidden" name="type" value="{{ $type }}">

                <!-- Name Input -->
                <input type="text" name="name_{{ $category->id }}" :disabled="!isEditing"
                    class="flex-1 min-w-0 rounded-md border-gray-200 text-gray-900 dark:text-white disabled:opacity-100"
                    value="{{ old('name_' . $category->id, $category->name) }}" />

                <!-- Color Picker -->
                <div @click.stop>
                    <div id="color-picker-{{ $category->id }}"
                        :class="isEditing ? '' : 'pointer-events-none opacity-50'"></div>
                    <input type="hidden" name="color_{{ $category->id }}" id="selectedColor{{ $category->id }}"
                        :disabled="!isEditing" value="{{ old('color_' . $category->id, $category->color) }}"
                        class="rounded-md border-gray-200 text-gray-900 dark:text-white disabled:opacity-100">
                </div>

                <!-- Save Button -->
                <template x-if="isEditing">
                    <button type="submit"
                        class="bg-emerald-500 text-white p-2 px-3 rounded-md flex items-center justify-center hover:bg-emerald-600 shadow-sm shrink-0">
                        <x-heroicon-s-check class="w-4 h-4 mr-1" />
                        Save
                    </button>
                </template>
            </form>

            <!-- Edit & Delete Buttons -->
            <template x-if="!isEditing">
                <div class="flex space-x-2 shrink-0">
                    <!-- Edit Button -->
                    <button type="button" @click="isEditing = true"
                        class="bg-orange-500 text-white p-2 px-3 rounded-md flex items-center justify-center hover:bg-orange-600 shadow-sm">
                        <x-heroicon-s-pencil-square class="w-4 h-4" />
                    </button>

                    <!-- Delete Form -->
                    <form :action="formAction()" method="POST" @submit.prevent="confirmDelete($event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 text-white p-2 px-3 rounded-md flex items-center justify-center hover:bg-red-600 shadow-sm">
                            <x-heroicon-s-trash class="w-4 h-4" />
                        </button>
                    </form>

                </div>
            </template>
        </div>

        @if ($errors->has('iconEdit_' . $category->id) || $errors->has('name_' . $category->id))
            <div class="text-red-700 text-sm mt-2 mb-2 px-5">
                <div class="bg-red-200 px-5 py-2 rounded-md">
                    @if ($errors->has('iconEdit_' . $category->id))
                        <div class="ml-2">
                            {{ $errors->first('iconEdit_' . $category->id) }}
                        </div>
                    @endif
                    @if ($errors->has('name_' . $category->id))
                        <div class="ml-2">
                            {{ $errors->first('name_' . $category->id) }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@empty
    <p class="text-gray-500">No categories available.</p>
@endforelse

<script>
    function confirmDelete(event) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Delete this category?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit();
            }
        });
    }
</script>
