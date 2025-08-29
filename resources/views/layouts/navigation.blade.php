<nav class="flex flex-col flex-1 relative p-4 border-r border-gray-300 min-h-0">
    <!-- Hamburger button (only on small screens) -->
    <div class="lg:hidden flex items-center justify-between bg-white text-black mb-4">
        <button @click="$dispatch('toggle-sidebar')" class=" focus:outline-none">
            <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M4 5h16v2H4V5zm0 6h16v2H4v-2zm0 6h16v2H4v-2z" />
            </svg>
        </button>

    </div>

    <!-- Sidebar content -->
    <div :class="{ 'block': open, 'hidden': !open }"
        class="lg:flex flex-1 flex-col p-2 bg-white text-black overflow-y-auto min-h-0">

        <a href="{{ route('dashboard') }}" class="flex items-center justify-center mb-5">
            <x-application-logo class="block w-auto fill-current mr-4 dark:text-gray-200" />
            <span class="text-xl font-black"> Money Tracker </span>
        </a>

        <div class="mb-5 bg-gray-300 rounded-md p-2">
            <h1 class="text-center font-black text-xl lg:text-3xl">{{ Auth::user()->currency_symbol }}
                {{ floor($remainingIncome) != $remainingIncome
                    ? number_format($remainingIncome, 2)
                    : number_format($remainingIncome, 0) }}
            </h1>
            <h1 class="text-center text-xs lg:text-sm">Remaining Income</h1>
        </div>

        <div class="flex flex-col flex-1">
            <a href="{{ route('transaction.index') }}"
                class="flex items-center justify-center w-full mb-10 bg-gray-300 font-black text-sm lg:text-lg p-2 text-center rounded-md hover:bg-gray-400 transition duration-200">
                <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                Add Transaction
            </a>

            <a href="{{ route('dashboard') }}"
                class="flex items-center justify-start font-medium p-2 text-xl rounded-md hover:bg-gray-300 transition duration-200 {{ request()->segment(1) == 'dashboard' ? 'bg-green-700 text-white hover:text-black' : '' }} ">
                <x-heroicon-s-home class="w-8 h-8 mr-4 shrink-0" />
                Dashboard
            </a>

            <a href="{{ route('income.index') }}"
                class="flex items-center justify-start font-medium p-2 text-xl rounded-md hover:bg-gray-300 transition duration-200 {{ request()->segment(1) == 'income' ? 'bg-green-700 text-white hover:text-black' : '' }}">
                <x-heroicon-s-banknotes class="w-8 h-8 mr-4 text-blue-400 " />
                Income
            </a>

            <a href="{{ route('expenses.index') }}"
                class="flex items-center justify-start font-medium p-2 text-xl rounded-md hover:bg-gray-300 transition duration-200 {{ request()->segment(1) == 'expenses' ? 'bg-green-700 text-white hover:text-black' : '' }}">
                <x-heroicon-s-credit-card class="w-8 h-8 mr-4 text-red-400" />
                Expenses
            </a>

            <a href="{{ route('savings.index') }}"
                class="flex items-center justify-start font-medium p-2 text-xl rounded-md hover:bg-gray-300 transition duration-200 {{ request()->segment(1) == 'savings' ? 'bg-green-700 text-white hover:text-black' : '' }}">
                <x-lucide-piggy-bank class="w-8 h-8 mr-4 text-green-400" />
                Savings
            </a>

            <a href="{{ route('dashboard') }}"
                class="flex items-center justify-start font-medium p-2 mb-5 text-xl rounded-md hover:bg-gray-300 transition duration-200 {{ request()->segment(1) == 'settings' ? 'bg-green-700 text-white hover:text-black' : '' }}">
                <x-heroicon-s-cog-8-tooth class="w-8 h-8 mr-4 text-gray-400" />
                Settings
            </a>

            <hr class="mt-auto border-black" />
        </div>

        <!-- Bottom section -->
        <div class="mb-2">
            <label for="currency" class="block text-sm font-medium text-white mb-2">Select Currency</label>
            <form method="POST" action="{{ route('currency.update') }}">
                @csrf
                @method('PATCH')
                <select id="currency" name="currency" onchange="this.form.submit()"
                    class="w-full select2 bg-white text-black rounded text-sm">
                    @foreach ($currencyList as $currency)
                        <option value='{"code":"{{ $currency['code'] }}","symbol":"{{ $currency['symbol'] }}"}'
                            {{ Auth::user()->currency_code == $currency['code'] ? 'selected' : '' }}>
                            {{ $currency['symbol'] }} {{ $currency['code'] }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="flex items-center w-full mt-5 mb-5 px-1 cursor-pointer">
            <x-dropdown align="right" width="full">
                <x-slot name="trigger" class="flex items-center justify-start rounded-md">
                    <img src="{{ asset('/default-avatar.png') }}" class="w-8 h-8 rounded-full mr-3" alt="User Avatar">
                    <h1 class="font-black text-xl">{{ Auth::user()->name }}</h1>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</nav>

<script>
    $(document).ready(function() {
        $('#currency').select2();
        $('#currency').next('.select2-container').css('width', '100%');
    });
</script>
