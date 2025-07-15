<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="max-w-sm mx-auto bg-white p-6 rounded-xl shadow-md border border-gray-100 transition-transform hover:scale-[1.02] duration-300">
        @csrf

        <!-- Title -->
        <h2 class="text-2xl font-bold text-center text-black mb-6 tracking-tight font-sans">
            {{ __('Register') }}
        </h2>

        <!-- Name -->
        <div class="mb-4">
            <x-input-label for="name" :value="__('Name')" class="block text-black font-medium mb-1" />
            <x-text-input id="name"
                class="block w-full rounded-md border border-gray-300 bg-gray-50 text-black placeholder-gray-400 focus:border-black focus:ring-black focus:ring-1 transition px-3 py-2"
                type="text"
                name="name"
                :value="old('name')"
                placeholder="Your full name"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" class="block text-black font-medium mb-1" />
            <x-text-input id="email"
                class="block w-full rounded-md border border-gray-300 bg-gray-50 text-black placeholder-gray-400 focus:border-black focus:ring-black focus:ring-1 transition px-3 py-2"
                type="email"
                name="email"
                :value="old('email')"
                placeholder="you@example.com"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" class="block text-black font-medium mb-1" />
            <x-text-input id="password"
                class="block w-full rounded-md border border-gray-300 bg-gray-50 text-black placeholder-gray-400 focus:border-black focus:ring-black focus:ring-1 transition px-3 py-2"
                type="password"
                name="password"
                placeholder="••••••••"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-black font-medium mb-1" />
            <x-text-input id="password_confirmation"
                class="block w-full rounded-md border border-gray-300 bg-gray-50 text-black placeholder-gray-400 focus:border-black focus:ring-black focus:ring-1 transition px-3 py-2"
                type="password"
                name="password_confirmation"
                placeholder="••••••••"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Google Login Button -->
        <div class="mb-4">
            <a href="{{ route('google.login') }}"
                class="flex items-center justify-center bg-white border border-gray-300 text-gray-800 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 rounded-md px-4 py-2 transition">
                <i class="fab fa-google mr-2"></i>
                <span class="font-semibold">Sign in with Google</span>
            </a>
        </div>

        <!-- Submit & Link to Login -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <a class="text-sm text-black hover:text-gray-700 font-semibold transition" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button
                class="w-full sm:w-auto bg-black hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-black rounded-md px-5 py-2.5 text-white font-semibold tracking-wide transition shadow">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
