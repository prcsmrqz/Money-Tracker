<nav class="flex flex-col flex-1 relative p-4 ">
    <!-- Hamburger button (only on small screens) -->
    <div class="lg:hidden flex items-center justify-between bg-gray-800 text-white mb-4">
        <button @click="$dispatch('toggle-sidebar')" class="text-gray-200 hover:text-white focus:outline-none">
            <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M4 5h16v2H4V5zm0 6h16v2H4v-2zm0 6h16v2H4v-2z" />
            </svg>
        </button>

    </div>

    <!-- Sidebar content -->
    <div :class="{ 'block': open, 'hidden': !open }"
        class="lg:flex flex-1 flex-col p-2 bg-gray-800 text-white overflow-y-auto min-h-0">

        <a href="{{ route('dashboard') }}" class="flex items-center justify-center mb-8">
            <x-application-logo class="block w-auto fill-current mr-4 text-gray-800 dark:text-gray-200" />
            <span class="text-2xl font-black"> Money Tracker </span>
        </a>

        <div class="mb-5 bg-gray-300 rounded-sm p-2 text-black">
            <h1 class="text-center font-black text-3xl">{{ Auth::user()->currency_symbol }} 1,000</h1>
            <h1 class="text-center text-sm">Income</h1>
        </div>

        <div class="flex flex-col flex-1">
            <a href="{{ route('dashboard') }}"
                class="flex items-center justify-center w-full mb-10 bg-gray-300 font-black text-black p-2 text-center rounded-sm hover:bg-gray-400 transition duration-200">
                <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                Add Transaction
            </a>

            <a href="{{ route('dashboard') }}"
                class="flex items-center justify-start font-black p-2 text-white text-2xl rounded-sm hover:bg-gray-600 hover:rounded-md transition duration-200">
                <x-heroicon-s-home class="w-10 h-10 mr-4 shrink-0" />
                Dashboard
            </a>

            <a href="{{ route('income.index') }}"
                class="flex items-center justify-start font-black p-2 text-white text-2xl rounded-sm hover:bg-gray-600 hover:rounded-md transition duration-200">
                <x-heroicon-s-banknotes class="w-10 h-10 mr-4 text-blue-500" />
                Income
            </a>

            <a href="{{ route('dashboard') }}"
                class="flex items-center justify-start font-black p-2 text-white text-2xl rounded-sm hover:bg-gray-600 hover:rounded-md transition duration-200">
                <x-heroicon-s-credit-card class="w-10 h-10 mr-4 text-red-500" />
                Expenses
            </a>

            <a href="{{ route('dashboard') }}"
                class="flex items-center justify-start font-black p-2 text-white text-2xl rounded-sm hover:bg-gray-600 hover:rounded-md transition duration-200">
                <x-heroicon-s-currency-dollar class="w-10 h-10 mr-4 text-green-500" />
                Savings
            </a>

            <a href="{{ route('dashboard') }}"
                class="flex items-center justify-start font-black p-2 mb-5 text-white text-2xl rounded-sm hover:bg-gray-600 hover:rounded-md transition duration-200">
                <x-heroicon-s-cog-8-tooth class="w-10 h-10 mr-4 text-gray-400" />
                Settings
            </a>

            <hr class="mt-auto mb-5" />
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
                <x-slot name="trigger" class="flex items-center justify-start rounded-sm">
                    <img src="{{ asset('/default-avatar.png') }}" class="w-10 h-10 rounded-full mr-3"
                        alt="User Avatar">
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
