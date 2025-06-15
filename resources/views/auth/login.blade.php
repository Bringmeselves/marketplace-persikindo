<x-guest-layout>
    <form method="POST" action="{{ route('login') }}"
        class="relative z-10 max-w-md mx-auto bg-white/30 backdrop-blur-md p-10 rounded-2xl shadow-2xl border border-white/20 transition-transform hover:scale-[1.02] duration-300">
        @csrf

        <!-- Title -->
        <h2 class="text-4xl font-extrabold text-center text-white mb-10 tracking-tight font-sans drop-shadow-lg">
            {{ __('Hello!') }}
        </h2>

        <!-- Email Address -->
        <div class="mb-6">
            <x-input-label for="email" :value="__('Email')" class="block text-white font-medium mb-2" />
            <x-text-input id="email"
                class="block w-full rounded-md border border-gray-300 bg-white/80 text-black placeholder-gray-500 focus:border-black focus:ring-black focus:ring-1 transition px-4 py-3"
                type="email"
                name="email"
                :value="old('email')"
                placeholder="you@example.com"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-500" />
        </div>

        <!-- Password -->
        <div class="mb-6">
            <x-input-label for="password" :value="__('Password')" class="block text-white font-medium mb-2" />
            <x-text-input id="password"
                class="block w-full rounded-md border border-gray-300 bg-white/80 text-black placeholder-gray-500 focus:border-black focus:ring-black focus:ring-1 transition px-4 py-3"
                type="password"
                name="password"
                placeholder="••••••••"
                required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-500" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center mb-8">
            <input id="remember_me" type="checkbox" name="remember"
                class="h-5 w-5 rounded border-gray-300 text-black focus:ring-black" />
            <label for="remember_me" class="ml-3 block text-sm text-white cursor-pointer font-medium">
                {{ __('Remember me') }}
            </label>
        </div>

        <!-- Submit & Forgot Password -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-sm text-white hover:underline font-semibold transition">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button
                class="w-full sm:w-auto bg-black hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-black rounded-md px-7 py-3 text-white font-semibold tracking-wide transition shadow-md">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
