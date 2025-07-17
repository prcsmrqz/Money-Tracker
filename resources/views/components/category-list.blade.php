@forelse ($categories as $category)
    <div x-data="{
        isEditing: false,
        previewUrlEdit: '{{ $category->icon ? asset("storage/$category->icon") : '' }}',
        action: '{{ $action }}',
        formId: {{ $category->id }},
        resetEdit() {
            this.isEditing = false;
            this.previewUrlEdit = '{{ $category->icon ? asset("storage/$category->icon") : '' }}';
            this.$refs.fileInput.value = null;
        },
        formAction() {
            return `${this.action}/${this.formId}`;
        }
    }" @custom-close-modal.window="resetEdit()"
        class="w-full border-b border-gray-200 p rounded-lg dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-900 dark:text-white">

        <div class="flex items-center justify-between w-full gap-2 flex-wrap sm:flex-nowrap">
            <!-- Edit Form -->
            <form :action="formAction()" method="POST" enctype="multipart/form-data"
                class="flex flex-1 items-center gap-2 flex-wrap sm:flex-nowrap">
                @csrf
                @method('PATCH')

                <!-- File Upload -->
                <label for="iconEdit{{ $category->id }}" :class="isEditing ? '' : 'pointer-events-none opacity-50'"
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
                        x-ref="fileInput" class="hidden"
                        @change="if (isEditing) { const file = $event.target.files[0]; if(file) previewUrlEdit = URL.createObjectURL(file); }">
                </label>

                <input type="hidden" name="type" value="{{ $type }}">

                <!-- Name Input -->
                <input type="text" name="name_{{ $category->id }}" :disabled="!isEditing"
                    class="flex-1 min-w-0 rounded-md border-gray-200 text-gray-900 dark:text-white disabled:opacity-100"
                    value="{{ old('name_' . $category->id, $category->name) }}" />

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
                    <form :action="formAction()" method="POST"
                        @submit.prevent="if (confirm('Delete this category?')) $event.target.submit()">
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
            <div class="text-red-700  text-sm mt-2 mb-2 px-5">
                <div class="bg-red-200 px-5 py-2 rounded-md">
                    @if ($errors->has('iconEdit_' . $category->id))
                        <div class=" ml-2">
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
<div x-data="heroIconPopup()" class="relative inline-block text-left w-full">

    <!-- Trigger Button -->
    <button type="button" @click="togglePopup" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Choose Icon
    </button>

    <!-- Hidden input (submit either heroicon name or custom file path) -->
    <input type="hidden" name="selected_icon" :value="isCustom ? customFileName : selectedIcon">

    <!-- Preview -->
    <div class="mt-2 flex items-center space-x-2" x-show="preview">
        <span class="text-sm text-gray-600">Selected:</span>
        <template x-if="isCustom">
            <img :src="preview" alt="custom icon" class="w-6 h-6 rounded border">
        </template>
        <template x-if="!isCustom && selectedIcon === 'bookmark'">
            <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3-7 3V5z" />
            </svg>
        </template>
        <span x-text="isCustom ? customFileName : selectedIcon" class="text-sm italic"></span>
    </div>

    <!-- Popup Panel -->
    <div x-show="open" @click.away="open = false" x-cloak
        class="absolute z-50 mt-2 w-72 p-4 bg-white border rounded shadow-md">

        <!-- Tabs -->
        <div class="flex justify-between mb-3">
            <button @click="isCustom = false" :class="isCustom ? 'text-gray-500' : 'font-bold text-blue-600'"
                class="text-sm focus:outline-none">
                Predefined
            </button>
            <button @click="isCustom = true" :class="isCustom ? 'font-bold text-blue-600' : 'text-gray-500'"
                class="text-sm focus:outline-none">
                Custom Upload
            </button>
        </div>

        <!-- Predefined Icon Grid -->
        <div x-show="!isCustom" class="grid grid-cols-4 gap-3">
            <div @click="selectIcon('bookmark')" :class="{ 'ring-2 ring-green-400': selectedIcon === 'bookmark' }"
                class="cursor-pointer p-1 rounded hover:bg-gray-100">
                <svg class="w-6 h-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3-7 3V5z" />
                </svg>
            </div>
        </div>

        <!-- Custom Upload -->
        <div x-show="isCustom" class="mt-2">
            <input type="file" accept="image/*" @change="handleCustomIcon" class="text-sm w-full">
        </div>
    </div>
</div>


<script>
    function heroIconPopup() {
        return {
            open: false,
            isCustom: false,
            selectedIcon: '',
            customFileName: '',
            preview: '',

            togglePopup() {
                this.open = !this.open;
            },

            selectIcon(name) {
                this.selectedIcon = name;
                this.isCustom = false;
                this.preview = '';
                this.open = false;
            },

            async handleCustomIcon(event) {
                const file = event.target.files[0];
                if (!file) return;

                this.customFileName = file.name;
                this.isCustom = true;

                // Show preview
                this.preview = URL.createObjectURL(file);

                // Optional: Upload to server here via AJAX
                // Or let Laravel handle it via form POST + enctype="multipart/form-data"

                this.open = false;
            }
        };
    }
</script>
