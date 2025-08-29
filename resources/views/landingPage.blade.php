<!-- Navbar -->
<header class="fixed top-0 left-0 w-full bg-white shadow-md z-50 ">
    <div class="flex flex-row items-center justify-between px-4 lg:px-20 py-3 border-b border-gray-300">
        <div class="flex flex-row items-center space-x-2 lg:space-x-4 animate-slide-in-left-delay">
            <x-application-logo class="block w-auto fill-current dark:text-gray-200 h-8 lg:h-12" />
            <a href="#home" class="font-bold text-sm lg:text-xl text-gray-700 dark:text-gray-200 ">
                Money Tracker
            </a>
        </div>

        <nav>
            <div class="flex flex-row items-center space-x-3 lg:space-x-6  animate-slide-in-right-delay">
                <a href="#how-it-works" class="text-gray-800 text-sm lg:text-base">How it
                    Works?</a>
                <a href="#features" class="text-gray-800 text-sm lg:text-base">Features</a>
                <a href="#faq" class="text-gray-800 text-sm lg:text-base">FAQ</a>
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="px-3 sm:px-4 md:px-5 py-1.5 sm:py-2 bg-green-600 text-sm lg:text-base text-white font-medium rounded-lg shadow-md hover:bg-green-600 ">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-3 sm:px-4 md:px-5 py-1.5 sm:py-2 bg-green-600 text-sm lg:text-base text-white font-medium rounded-lg shadow-md hover:bg-green-600">
                        Log in
                    </a>
                @endauth
            </div>
        </nav>
    </div>
</header>


<!-- Top Section -->
<section id="home" class="bg-cover bg-center min-h-screen pt-20 lg:pt-0"
    style="background-image: url('{{ asset('landingpage/bg3.png') }}');">
    <div class="px-8 lg:px-20 min-h-screen grid grid-cols-1 lg:grid-cols-[1000px_800px] items-center">
        <div class="flex flex-col items-center justify-center lg:items-start animate-slide-in-left-delay">
            <h1 class="font-bold text-8xl mb-6 text-center lg:text-left">TRACK YOUR MONEY</h1>
            <h2 class="font-bold text-4xl lg:text-5xl mb-6 text-gray-700 text-center lg:text-left lg:ml-5">
                Know Where Your <br class="block lg:hidden" /> Money Goes
            </h2>
            <p class="text-lg text-gray-700 mb-8 text-center lg:text-left lg:ml-5 max-w-xl">
                Stop guessing where your money goes — track every peso, visualize your spending,
                and take control of your finances.
            </p>

            <a href="{{ route('login') }}"
                class="px-8 py-4 w-[230px] bg-green-500 text-white font-bold flex items-center justify-center text-2xl 
                       rounded-lg shadow-[0_8px_10px_rgba(0,0,0,0.2)] 
                       hover:bg-green-600 hover:shadow-[0_12px_15px_rgba(0,0,0,0.3)] 
                       transform hover:-translate-y-1 
                       transition duration-300 ease-in-out 
                       mx-auto lg:mx-0 lg:ml-5">
                Get Started
                <x-heroicon-s-arrow-right class="w-7 h-9 ml-2" />
            </a>
        </div>

        <div>
            <img src="{{ asset('landingpage/laptopCellphone.png') }}" alt="Laptop Cellphone Image"
                class="w-[940px] -mt-6 animate-slide-in-right-delay">
        </div>
    </div>
</section>


<!-- How It Works Section -->
<section id="how-it-works" class="bg-gray-100 py-10 scroll-mt-16" data-aos="fade-up">
    <div class="px-8 lg:px-32 text-center  ">
        <h1 class="text-4xl font-bold mb-14 mt-10">How It Works</h1>
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
            <div class="flex flex-col items-center">
                <div class="bg-blue-100 p-5 rounded-full mb-4">
                    <x-lucide-plus-circle class="w-12 h-12 text-blue-500" />
                </div>
                <h3 class="font-bold text-xl mb-2">Add Transaction</h3>
                <p class="text-gray-600">Record income, expense, or savings.</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="bg-green-100 p-5 rounded-full mb-4">
                    <x-lucide-tag class="w-12 h-12 text-green-500" />
                </div>
                <h3 class="font-bold text-xl mb-2">Assign Category</h3>
                <p class="text-gray-600">Choose the right category for each transaction.</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="bg-red-100 p-5 rounded-full mb-4">
                    <x-lucide-bar-chart class="w-12 h-12 text-red-500" />
                </div>
                <h3 class="font-bold text-xl mb-2">View Insights</h3>
                <p class="text-gray-600">See charts, summaries, and trends for your money.</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="bg-yellow-100 p-5 rounded-full mb-4">
                    <x-lucide-filter class="w-12 h-12 text-yellow-500" />
                </div>
                <h3 class="font-bold text-xl mb-2">Filter & Search</h3>
                <p class="text-gray-600">Quickly find transactions by date or category.</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class=" bg-gradient-to-b from-blue-100 to-gray-100 scroll-mt-16 pb-20" data-aos="fade-up">
    <div class="px-14 lg:px-20">
        <div class="flex justify-center items-center mb-20">
            <h1 class="mt-20 text-4xl font-bold text-center">Powerful Tools for Your Finances</h1>
        </div>

        <div class="flex flex-col lg:flex-row justify-center items-center gap-8">
            <div
                class="bg-white w-full flex flex-col items-start rounded-xl p-8 py-12 border-t border-gray-100 shadow-[-4px_4px_10px_rgba(0,0,0,0.09),4px_4px_10px_rgba(0,0,0,0.09)]">
                <div class="bg-blue-400/20 p-3 rounded-xl mb-6">
                    <x-lucide-hand-coins class="w-12 h-12 text-blue-400" />
                </div>
                <div class="flex flex-col ml-3">
                    <h1 class="text-3xl font-bold mb-3 text-gray-600">Take full control</h1>
                    <h4 class="text-xl text-gray-500 leading-relaxed">
                        Manage income, expenses, and savings in one secure place.
                    </h4>
                </div>
            </div>

            <div
                class="bg-white w-full flex flex-col items-start rounded-xl p-8 py-12 border-t border-gray-100 shadow-[-4px_4px_10px_rgba(0,0,0,0.09),4px_4px_10px_rgba(0,0,0,0.09)]">
                <div class="bg-red-400/20 p-3 rounded-xl mb-6">
                    <x-lucide-hand-coins class="w-12 h-12 text-red-400" />
                </div>
                <div class="flex flex-col ml-3">
                    <h1 class="text-3xl font-bold mb-3 text-gray-600">Track with clarity</h1>
                    <h4 class="text-xl text-gray-500 leading-relaxed">
                        See summaries of your income, expenses, and savings in real time.
                    </h4>
                </div>
            </div>

            <div
                class="bg-white w-full flex flex-col items-start rounded-xl p-8 py-12 border-t border-gray-100 shadow-[-4px_4px_10px_rgba(0,0,0,0.09),4px_4px_10px_rgba(0,0,0,0.09)]">
                <div class="bg-green-500/20 p-3 rounded-xl mb-6">
                    <x-lucide-hand-coins class="w-12 h-12 text-green-500" />
                </div>
                <div class="flex flex-col ml-3">
                    <h1 class="text-3xl font-bold mb-3 text-gray-600">Track better and smarter</h1>
                    <h4 class="text-xl text-gray-500 leading-relaxed">
                        Log every transaction, filter easily, and discover where your money goes.
                    </h4>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="faq" class="bg-gray-100 py-20" data-aos="fade-up">
    <div class="px-12 lg:px-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="flex justify-center items-start mt-5">
                <img src="{{ asset('landingpage/faq.png') }}" alt="FAQ Image" class="w-auto">
            </div>

            <div>
                <h2 class="text-4xl font-bold text-center mb-10">Frequently Asked Questions</h2>

                <div x-data="{ openSections: [] }" class="space-y-5">

                    <div class="w-full border-b border-gray-300"
                        :class="openSections.includes(1) ? 'bg-green-50 border border-green-300 shadow-md rounded-xl' :
                            'hover:shadow-sm rounded-xl transition'">
                        <button
                            class="px-5 py-4 text-lg font-medium text-gray-800 flex items-center justify-between w-full text-left 
                                   transition duration-300 ease-in-out hover:bg-gray-50 rounded-xl"
                            @click="openSections.includes(1) ? openSections = openSections.filter(i => i !== 1) : openSections.push(1)">
                            <span>Is Money Tracker <b>free</b>?</span>
                            <div class="bg-green-500 rounded-full p-2 flex items-center justify-center transition-transform duration-300"
                                :class="openSections.includes(1) ? 'rotate-180' : ''">
                                <x-heroicon-s-plus class="w-4 h-4 text-white" x-show="!openSections.includes(1)" />
                                <x-heroicon-s-minus class="w-4 h-4 text-white" x-show="openSections.includes(1)" />
                            </div>
                        </button>
                        <div x-show="openSections.includes(1)" x-collapse
                            class="px-5 pb-5 text-gray-600 text-base leading-relaxed">
                            Yes, the money tracker is <b>free</b> to use for <b>all users</b>.
                        </div>
                    </div>

                    <div class="w-full border-b border-gray-300"
                        :class="openSections.includes(2) ? 'bg-green-50 border border-green-300 shadow-md rounded-xl' :
                            'hover:shadow-sm rounded-xl transition'">
                        <button
                            class="px-5 py-4 text-lg font-medium text-gray-800 flex items-center justify-between w-full text-left 
                                   transition duration-300 ease-in-out hover:bg-gray-50 rounded-xl"
                            @click="openSections.includes(2) ? openSections = openSections.filter(i => i !== 2) : openSections.push(2)">
                            <span>Can I use it on <b>mobile</b>?</span>
                            <div class="bg-green-500 rounded-full p-2 flex items-center justify-center transition-transform duration-300"
                                :class="openSections.includes(2) ? 'rotate-180' : ''">
                                <x-heroicon-s-plus class="w-4 h-4 text-white" x-show="!openSections.includes(2)" />
                                <x-heroicon-s-minus class="w-4 h-4 text-white" x-show="openSections.includes(2)" />
                            </div>
                        </button>
                        <div x-show="openSections.includes(2)" x-collapse
                            class="px-5 pb-5 text-gray-600 text-base leading-relaxed">
                            Absolutely! Money Tracker is <b>fully responsive</b> and works on any device.
                        </div>
                    </div>

                    <div class="w-full border-b border-gray-300"
                        :class="openSections.includes(3) ? 'bg-green-50 border border-green-300 shadow-md rounded-xl' :
                            'hover:shadow-sm rounded-xl transition'">
                        <button
                            class="px-5 py-4 text-lg font-medium text-gray-800 flex items-center justify-between w-full text-left 
                                   transition duration-300 ease-in-out hover:bg-gray-50 rounded-xl"
                            @click="openSections.includes(3) ? openSections = openSections.filter(i => i !== 3) : openSections.push(3)">
                            <span>Is my financial <b>data safe</b>?</span>
                            <div class="bg-green-500 rounded-full p-2 flex items-center justify-center transition-transform duration-300"
                                :class="openSections.includes(3) ? 'rotate-180' : ''">
                                <x-heroicon-s-plus class="w-4 h-4 text-white" x-show="!openSections.includes(3)" />
                                <x-heroicon-s-minus class="w-4 h-4 text-white" x-show="openSections.includes(3)" />
                            </div>
                        </button>
                        <div x-show="openSections.includes(3)" x-collapse
                            class="px-5 pb-5 text-gray-600 text-base leading-relaxed">
                            Yes, all your data is <b>encrypted</b> and <b>stored securely</b>.
                        </div>
                    </div>

                    <div class="w-full border-b border-gray-300"
                        :class="openSections.includes(4) ? 'bg-green-50 border border-green-300 shadow-md rounded-xl' :
                            'hover:shadow-sm rounded-xl transition'">
                        <button
                            class="px-5 py-4 text-lg font-medium text-gray-800 flex items-center justify-between w-full text-left 
                                   transition duration-300 ease-in-out hover:bg-gray-50 rounded-xl"
                            @click="openSections.includes(4) ? openSections = openSections.filter(i => i !== 4) : openSections.push(4)">
                            <span>Does <b>Money Tracker</b> support multiple currencies?</span>
                            <div class="bg-green-500 rounded-full p-2 flex items-center justify-center transition-transform duration-300"
                                :class="openSections.includes(4) ? 'rotate-180' : ''">
                                <x-heroicon-s-plus class="w-4 h-4 text-white" x-show="!openSections.includes(4)" />
                                <x-heroicon-s-minus class="w-4 h-4 text-white" x-show="openSections.includes(4)" />
                            </div>
                        </button>
                        <div x-show="openSections.includes(4)" x-collapse
                            class="px-5 pb-5 text-gray-600 text-base leading-relaxed">
                            Yes, you can track your income and expenses in <b>different currencies</b>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Call to Action Section -->
<section class="bg-green-500 py-20">
    <div class="px-8 lg:px-32 text-center text-white">
        <h2 class="text-4xl font-bold mb-6">Start Tracking Today</h2>
        <p class="mb-8">Take control of your finances and make smarter money decisions.</p>
        <a href="{{ route('login') }}"
            class="px-8 py-4 bg-white text-green-500 font-bold rounded-lg shadow-md hover:bg-gray-100 transition">
            Get Started
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-100 py-10">
    <div class="px-8 lg:px-32 flex flex-col lg:flex-row justify-between items-center gap-6">
        <div class="flex flex-col items-center lg:items-start">
            <x-application-logo />
            <p class="text-gray-600 text-sm">&copy; 2025 Money Tracker. All rights reserved.</p>
        </div>
        <div class="flex gap-4">
            <a href="#" class="text-gray-600 hover:text-gray-800">Terms</a>
            <a href="#" class="text-gray-600 hover:text-gray-800">Privacy</a>
            <a href="#contact" class="text-gray-600 hover:text-gray-800">Contact</a>
        </div>
    </div>
</footer>
