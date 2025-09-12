<x-guest-layout>

    <h1 class="font-bold text-gray-600 text-2xl mb-4">
        {{ __('Login') }}
    </h1>
    <hr class="mb-5" />
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded  border-gray-300  text-indigo-600 shadow-sm focus:ring-indigo-500 :ring-indigo-600 :ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 ">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col items-center gap-5 mt-4 mb-8">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600  hover:text-gray-900 :text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 :ring-offset-gray-800"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="w-full text-center items-center">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div>
            @if (Route::has('register'))
                <span class="text-sm text-gray-600">Don't have an account?</span>
                <a class="underline text-sm text-green-600 font-medium hover:text-gray-900 rounded-md 
                        focus:outline-none hover:text-green-700"
                    href="{{ route('register') }}">
                    {{ __('Register here.') }}
                </a>
            @endif

        </div>
    </form>
</x-guest-layout>
