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
