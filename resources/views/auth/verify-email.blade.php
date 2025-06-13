<x-guest-layout>
    <div class="max-w-sm mx-auto bg-white p-6 rounded-xl shadow-md border border-gray-100 transition-transform hover:scale-[1.02] duration-300">
        <!-- Info Message -->
        <div class="mb-4 text-sm text-gray-700">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        <!-- Success Status -->
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Resend Verification -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-black text-white font-medium text-sm px-4 py-2.5 rounded-md shadow-md hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-all">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            <!-- Log Out -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-black text-white font-medium text-sm px-4 py-2.5 rounded-md shadow-md hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-all">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
