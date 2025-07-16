@props(['align' => 'right', 'width' => 'full', 'contentClasses' => 'py-1 bg-white dark:bg-gray-700'])

@php
    // 'start-0' for left align, 'end-0' for right align
    $alignmentClasses = match ($align) {
        'left' => 'start-0',
        'right' => 'end-0',
        default => '',
    };

    $width = match ($width) {
        '48' => 'w-48',
        'full' => 'w-full',
        default => $width,
    };
@endphp

<div class="relative w-full" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <!-- Trigger (should be full width too) -->
    <div @click="open = ! open" class="flex items-center cursor-pointer w-full">
        {{ $trigger }}
    </div>

    <!-- Dropdown content -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="absolute bottom-full mb-2 {{ $alignmentClasses }} {{ $width }} rounded-md shadow-lg {{ $contentClasses }}"
        style="display: none;" @click="open = false">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
