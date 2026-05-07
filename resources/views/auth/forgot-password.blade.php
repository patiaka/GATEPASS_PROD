<x-guest-layout>

    <!-- Logo -->
    {{-- <div class="flex justify-center mb-6">
        <x-logo />
    </div> --}}

    <!-- Title -->
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-[#134169]">
            Forgot Password
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            Enter your email to receive a reset link
        </p>
    </div>

    <!-- Error -->
    @error('email')
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm">
            {{ $message }}
        </div>
    @enderror

    <!-- Success Message -->
    @if (session('status'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-600 text-sm flex items-center gap-2">
            <!-- Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M5 13l4 4L19 7" />
            </svg>

            <span>
                Password reset link has been sent to your email.
            </span>
        </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            {{-- <label class="block text-sm font-medium text-gray-700 mb-1">
                Email Address
            </label> --}}

            <div class="relative">

                <!-- Icon -->
                {{-- <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M16 12H8m0 0l4-4m-4 4l4 4" />
                    </svg>
                </span> --}}

                <!-- Input -->
                <input type="email" name="email" placeholder="Enter your email" required autofocus
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50
                      text-sm text-gray-700 placeholder-gray-400
                      focus:bg-white focus:ring-2 focus:ring-[#134169]/30 focus:border-[#134169]
                      outline-none transition duration-200">
            </div>
        </div>

        <!-- Button -->
        <button type="submit"
            class="w-full py-3 rounded-xl bg-[#134169] text-white text-base font-semibold
                   hover:bg-[#0f3554] active:scale-[0.98] transition duration-200
                   shadow-sm flex items-center justify-center gap-2">

            <span>Send Reset Link</span>

            <!-- Icon -->
            {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M16 12H8m0 0l4-4m-4 4l4 4" />
            </svg> --}}
        </button>

        <!-- 2FA -->
        @if (Session::has('two_factor:user_id'))
            <div class="text-center text-sm text-gray-500">
                <a href="{{ route('2fa_verify_code') }}" class="text-[#134169] hover:underline">
                    Resend verification code
                </a>
            </div>
        @endif

    </form>

    <!-- Back -->
    <div class="text-center mt-6">
        <a href="{{ route('login') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#134169] transition">

            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M15 18l-6-6 6-6" />
            </svg>

            Back to login
        </a>
    </div>

</x-guest-layout>