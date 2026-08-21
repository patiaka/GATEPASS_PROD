<x-guest-layout>

    {{-- En-tête --}}
    <div class="flex items-center gap-3 mb-5">
        <span class="flex items-center justify-center w-11 h-11 shrink-0 rounded-full bg-[#134169]/10 text-[#134169] ring-1 ring-[#134169]/15">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                <path d="M7 11V7a5 5 0 0 1 9.9-1" />
            </svg>
        </span>
        <div>
            <h1 class="text-lg font-bold text-[#134169] leading-tight">Forgot your password?</h1>
            <p class="text-xs text-slate-500">Enter your email and we'll send you a reset link.</p>
        </div>
    </div>

    {{-- Message de succès --}}
    @if (session('status'))
        <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-3 mb-4 flex items-start gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-xs text-emerald-700">A password reset link has been sent to your email.</span>
        </div>
    @endif

    {{-- Erreur --}}
    @error('email')
        <div class="rounded-lg bg-rose-50 border border-rose-200 p-3 mb-4 flex items-start gap-2">
            <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
            </svg>
            <span class="text-xs text-rose-700">{{ $message }}</span>
        </div>
    @enderror

    <form method="POST" action="{{ route('password.email') }}" class="grid gap-4">
        @csrf

        <div class="flex flex-col">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="20" height="16" x="2" y="4" rx="2" />
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                    </svg>
                </span>
                <input type="email" id="email" name="email" placeholder="Enter your email" required autofocus
                    autocomplete="email"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900
                           focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none transition">
            </div>
        </div>

        <button type="submit"
            class="w-full bg-[#134169] text-white font-semibold py-2.5 px-4 rounded-lg hover:bg-[#0e3354] transition duration-200
                   focus:outline-none focus:ring-2 focus:ring-[#134169]/40 active:scale-[0.99] shadow-sm">
            Send reset link
        </button>

        @if (Session::has('two_factor:user_id'))
            <div class="text-center text-sm text-slate-500">
                <a href="{{ route('2fa_verify_code') }}" class="text-[#134169] hover:underline">Resend verification code</a>
            </div>
        @endif
    </form>

    {{-- Retour --}}
    <div class="text-center mt-5">
        <a href="{{ route('login') }}"
            class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-[#134169] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
            </svg>
            Back to login
        </a>
    </div>

</x-guest-layout>
