@props([
    'iconMessage' => 'Default Icon Message',
    'chartMessage' => 'Default Chart Message',
])
<div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-md shadow">
    <div x-show="activeTab === 'icon'" x-cloak>
        <p class="text-gray-800 dark:text-gray-200">{{ $iconMessage }}</p>
    </div>
    <div x-show="activeTab === 'chart'" x-cloak>
        <p class="text-gray-800 dark:text-gray-200">{{ $chartMessage }}</p>
    </div>
</div>
