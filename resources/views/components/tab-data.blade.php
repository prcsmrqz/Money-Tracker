@props([
    'iconMessage' => 'Default Icon Message',
    'chartMessage' => 'Default Chart Message',
])
<div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-md shadow w-full">
    <div x-show="activeTab === 'icon'" x-cloak>
        <p class="text-gray-800 dark:text-gray-200 text-base sm:text-lg">{{ $iconMessage }}</p>
    </div>
    <div x-show="activeTab === 'chart'" x-cloak>
        <p class="text-gray-800 dark:text-gray-200 text-base sm:text-lg">{{ $chartMessage }}</p>
    </div>
</div>
