<x-app-layout>
    <x-title-header>
        <a href="{{ route($category->type . '.index') }}" class="capitalize">
            <span class="hover:underline">{{ $category->type }}</span>
            > {{ $category->name }}
        </a>
    </x-title-header>

    <div class="px-4 sm:px-6 lg:px-10">
        <div class="mb-10 py-5 px-4 bg-white sm:px-6 lg:px-10 dark:bg-gray-800 rounded-md shadow-lg w-full">
            <div style="background-image: 
                        radial-gradient(circle at left center, {{ $category->color }} 10%, transparent 90%),
                        radial-gradient(circle at right center, {{ $category->color }} 10%, transparent 90%);"
                class="mb-4 p-3 rounded-md shadow text-white text-center">
                <h1 class="text-xl sm:text-2xl font-bold capitalize">{{ $category->name }} Transaction List
                </h1>
            </div>




            <table
                class="hidden sm:table w-full table-fixed text-sm sm:text-base text-left text-gray-800 dark:text-gray-200">

                <tbody>
                    @foreach ($transactions as $date => $dailyTransactions)
                        <tr class="bg-gray-100 border-b border-gray-300 rounded-md">
                            <td colspan="5" class="px-4 py-4 ">
                                <div
                                    class="flex flex-wrap sm:flex-nowrap items-center justify-between gap-4 sm:gap-6 font-semibold text-sm sm:text-lg">
                                    <div class="text-black px-6 dark:text-gray-200 min-w-[140px]">
                                        {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
                                    </div>
                                    <div class="flex gap-4 sm:gap-20 flex-wrap sm:flex-nowrap text-sm sm:text-lg">
                                        <div class="text-blue-500 min-w-[100px] flex flex-col items-center text-center">
                                            <span class="whitespace-nowrap font-semibold">
                                                {{ Auth::user()->currency_symbol }}{{ number_format($sumByTypePerDate[$date]['income'] ?? 0, 2) }}
                                            </span>
                                            <span class="text-xs font-normal text-gray-500">Income</span>
                                        </div>
                                        <div class="text-red-500 min-w-[100px] flex flex-col items-center text-center">
                                            <span class="whitespace-nowrap font-semibold">
                                                {{ Auth::user()->currency_symbol }}{{ number_format($sumByTypePerDate[$date]['expenses'] ?? 0, 2) }}
                                            </span>
                                            <span class="text-xs font-normal text-gray-500">Expenses</span>
                                        </div>
                                        <div
                                            class="text-emerald-500 min-w-[100px] flex flex-col items-center text-center mr-10">
                                            <span class="whitespace-nowrap font-semibold">
                                                {{ Auth::user()->currency_symbol }}{{ number_format($sumByTypePerDate[$date]['savings'] ?? 0, 2) }}
                                            </span>
                                            <span class="text-xs font-normal text-gray-500">Savings</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        @foreach ($dailyTransactions as $transaction)
                            @php
                                $typeClass = match ($transaction->type) {
                                    'income' => 'text-blue-600 bg-blue-100 dark:bg-blue-700',
                                    'expenses' => 'text-red-600 bg-red-100 dark:bg-red-700',
                                    'savings' => 'text-emerald-600 bg-emerald-100 dark:bg-emerald-700',
                                    default => '',
                                };
                            @endphp
                            <tr
                                class="border-b border-gray-200 text-gray-500 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="w-1/6 px-10 py-3 whitespace-nowrap text-sm">
                                    {{ $transaction->date->format('h:i A') }}
                                </td>
                                <td class="w-1/6 px-4 py-3 capitalize font-semibold">
                                    <span class="{{ $typeClass }} px-3 py-1 rounded-full text-sm inline-block">
                                        {{ $transaction->type }}
                                    </span>
                                </td>
                                <td class="w-1/6 px-4 py-3 whitespace-nowrap text-sm">
                                    {{ Auth::user()->currency_symbol }}
                                    {{ number_format($transaction->amount, 2) }}
                                </td>
                                <td class="w-2/6 px-4 py-3">
                                    <div
                                        class="line-clamp-2 break-words text-ellipsis text-sm {{ $transaction->notes ? '' : 'text-gray-400 italic' }}">
                                        {{ $transaction->notes ?: 'No notes provided' }}
                                    </div>
                                </td>
                                <td class="w-1/5 px-4 py-3 space-x-2 whitespace-nowrap flex gap-2">
                                    <a href="#" class="text-blue-500 hover:underline ">
                                        <x-heroicon-s-eye class="w-5 h-5" />
                                    </a>
                                    <a href="#" class="text-yellow-500 hover:underline ">
                                        <x-heroicon-s-pencil-square class="w-5 h-5" />
                                    </a>
                                    <a href="#" class="text-red-500 hover:underline">
                                        <x-heroicon-s-trash class="w-5 h-5" />
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4 hidden sm:block">
                {{-- Pagination links --}}
                {{ $transactions->links('pagination::tailwind') }}
            </div>

            {{-- Mobile Card View --}}
            <div class="block sm:hidden space-y-4">
                @foreach ($transactions as $date => $dailyTransactions)
                    <div class="text-gray-700 dark:text-gray-200 font-semibold">
                        {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
                    </div>

                    @foreach ($dailyTransactions as $transaction)
                        @php
                            $typeClass = match ($transaction->type) {
                                'income' => 'text-blue-600 bg-blue-100 dark:bg-blue-700',
                                'expenses' => 'text-red-600 bg-red-100 dark:bg-red-700',
                                'savings' => 'text-emerald-600 bg-emerald-100 dark:bg-emerald-700',
                                default => '',
                            };
                        @endphp

                        <div class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-700 shadow">
                            <div class="flex justify-between mb-2">
                                <span
                                    class="text-sm text-gray-500 dark:text-gray-300">{{ $transaction->date->format('h:i A') }}</span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $typeClass }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </div>
                            <div class="mb-1 font-bold">
                                {{ Auth::user()->currency_symbol }}{{ number_format($transaction->amount, 2) }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-300 break-words line-clamp-2">
                                {{ $transaction->notes }}
                            </div>
                            <div class="mt-3 flex gap-3 text-sm flex gap-2">
                                <a href="#" class="text-blue-500 hover:underline">
                                    <x-heroicon-s-eye class="w-5 h-5" />
                                </a>
                                <a href="#" class="text-yellow-500 hover:underline">
                                    <x-heroicon-s-pencil-square class="w-5 h-5" />
                                </a>
                                <a href="#" class="text-red-500 hover:underline">
                                    <x-heroicon-s-trash class="w-5 h-5" />
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endforeach
                <div class="mt-4 block sm:hidden">
                    {{-- Pagination links --}}
                    {{ $transactions->links('pagination::tailwind') }}
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
