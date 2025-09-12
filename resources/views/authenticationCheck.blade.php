<div class="min-h-screen flex flex-col items-center justify-center">
    @if (Route::has('login'))
        <div class="space-x-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="font-semibold text-indigo-600 hover:text-indigo-800">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-800">Register
                        sample</a>
                @endif
            @endauth
        </div>
    @endif
</div>
<section class="bg-cover bg-center" style="background-image: url('{{ asset('landingpage/bg1.png') }}');">
    <header class="fixed top-0 left-0 w-full bg-white shadow-md z-50">
        <div class="py-6 px-8 lg:px-20 ">
            <div
                class="flex flex-row items-center justify-between pb-4 border-b border-gray-300 mb-8 lg:mb-40 animate-fade-in ">
                <div class="flex flex-row items-center space-x-3">
                    <x-application-logo class="block w-auto fill-current  animate-slide-in-left" />
                    <a href="{{ route('home') }}"
                        class="font-bold text-sm lg:text-xl text-gray-700  animate-slide-in-left">
                        Money Tracker
                    </a>
                </div>

                <nav>
                    @if (Route::has('login'))
                        <div class="flex flex-row items-center space-x-3 lg:space-x-5">
                            <a href="{{ route('register') }}"
                                class="text-gray-800 text-sm lg:text-base animate-slide-in-right">Home</a>
                            <a href="{{ route('register') }}"
                                class="text-gray-800 text-sm lg:text-base animate-slide-in-right">About</a>
                            <a href="{{ route('register') }}"
                                class="text-gray-800 text-sm lg:text-base animate-slide-in-right">Contact</a>
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="px-5 py-2 ml-5 bg-green-500 text-white font-medium flex items-center justify-center
                                rounded-lg shadow-md animate-slide-in-right
                                hover:bg-green-600">
                                    Dashboard
                                </a>
                            @else
                                {{--
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 border border-green-500 text-black font-medium flex items-center justify-center
                                    rounded-lg animate-slide-in-right
                                    hover:bg-green-600 hover:text-white">Register</a>
                            @endif
                            --}}
                                <a href="{{ route('login') }}"
                                    class="px-5 py-2 bg-green-600 text-xs text-white font-medium flex items-center justify-center
                                rounded-lg shadow-md animate-slide-in-right
                                hover:bg-green-500">
                                    Log in
                                </a>
                            @endauth
                        </div>
                    @endif
                </nav>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-[1000px_800px]">
                <div class="flex flex-col items-center justify-center lg:items-start">
                    <h1 class="font-bold text-8xl mb-5 animate-fade-in text-center lg:text-left">
                        TRACK YOUR MONEY
                    </h1>
                    <h1
                        class="font-bold text-4xl lg:text-5xl mb-10 text-gray-700 animate-fade-in-delay text-center lg:text-left lg:ml-5">
                        Know Where Your <br class="block lg:hidden" /> Money Goes
                    </h1>
                    <button
                        class="px-8 py-4 w-[230px] bg-green-500 text-white font-bold flex items-center justify-center text-2xl 
            rounded-lg shadow-[0_8px_10px_rgba(0,0,0,0.2)] 
            hover:bg-green-600 hover:shadow-[0_12px_15px_rgba(0,0,0,0.3)] 
            transform hover:-translate-y-1 
            transition duration-300 ease-in-out animate-fade-in-delay 
            mx-auto lg:mx-0 lg:ml-5">
                        Get Started
                        <x-heroicon-s-arrow-right class="w-7 h-9 ml-2" />
                    </button>
                </div>

                <div class="">
                    <img src="{{ asset('landingpage/laptopCellphone.png') }}" alt="Laptop Cellphone Image"
                        class="w-[940px] mt-6 lg:-mt-16 animate-fade-in-delay">
                </div>
            </div>
        </div>
    </header>
</section>
<section class=" mt-10 mb-10">
    <div class=" px-8 lg:px-40">
        <div class="flex flex-col lg:flex-row justify-center items-center gap-10">
            <div
                class="bg-white flex items-center rounded-lg p-6 border-t border-gray-100 shadow-[-4px_4px_10px_rgba(0,0,0,0.1),4px_4px_10px_rgba(0,0,0,0.1)]">
                <div class="bg-blue-400/20 p-3 rounded-full mr-4 flex-shrink-0">
                    <x-heroicon-s-adjustments-vertical class="w-12 h-12 text-blue-400" />
                </div>

                <div class="flex flex-col text-start">
                    <h1 class="text-2xl font-bold mb-2 text-gray-600">Take full control</h1>
                    <h4 class="text-lg text-gray-500">
                        of your income, daily expenses, savings, and all financial accounts — in one secure place.
                    </h4>
                </div>
            </div>

            <div
                class="bg-white flex items-center rounded-lg p-6 border-t border-gray-100 shadow-[-4px_4px_10px_rgba(0,0,0,0.1),4px_4px_10px_rgba(0,0,0,0.1)]">
                <div class="bg-red-400/20 p-3 rounded-full mr-4 flex-shrink-0">
                    <x-lucide-hand-coins class="w-12 h-12 text-red-400" />
                </div>

                <div class="flex flex-col text-start">
                    <h1 class="text-2xl font-bold mb-2 text-gray-600">See your money clearly</h1>
                    <h4 class="text-lg text-gray-500">
                        with real-time summaries of your total income, spending, and savings
                        progress.
                    </h4>
                </div>
            </div>

            <div
                class="bg-white flex items-center rounded-lg p-6 border-t border-gray-100 shadow-[-4px_4px_10px_rgba(0,0,0,0.1),4px_4px_10px_rgba(0,0,0,0.1)]">
                <div class="bg-green-500/20 p-3 rounded-full mr-4 flex-shrink-0">
                    <x-lucide-chart-no-axes-combined class="w-12 h-12 text-green-500" />
                </div>

                <div class="flex flex-col text-start">
                    <h1 class="text-2xl font-bold mb-2 text-gray-600">Smarter tracking, better saving</h1>
                    <h4 class="text-lg text-gray-500">
                        by logging every transaction, filter with ease, and discover where your
                        money really goes.
                    </h4>
                </div>
            </div>
        </div>
    </div>
</section>
