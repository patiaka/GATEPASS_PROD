<x-guest-layout>

    {{-- En-tête --}}
    <div class="text-center mb-7">
        <div class="mx-auto mb-4 flex items-center justify-center w-14 h-14 rounded-2xl bg-[#134169]/10 text-[#134169] ring-1 ring-[#134169]/15">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                <path d="M7 11V7a5 5 0 0 1 9.9-1" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-[#134169]">Forgot your password?</h1>
        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
            No worries — enter your email and we'll send you a link to reset it.
        </p>
    </div>

    {{-- Message de succès --}}
    @if (session('status'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-3.5 mb-5 flex items-start gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-sm text-emerald-700 leading-relaxed">A password reset link has been sent to your email.</span>
        </div>
    @endif

    {{-- Erreur --}}
    @error('email')
        <div class="rounded-xl bg-rose-50 border border-rose-200 p-3.5 mb-5 flex items-start gap-2.5">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
            </svg>
            <span class="text-sm text-rose-700 leading-relaxed">{{ $message }}</span>
        </div>
    @enderror

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email address</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect width="20" height="16" x="2" y="4" rx="2" />
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                    </svg>
                </span>
                <input type="email" id="email" name="email" placeholder="you@somisy.com" required autofocus
                    autocomplete="email"
                    class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 bg-gray-50 text-[15px] text-gray-900 placeholder-slate-400
                           focus:bg-white focus:ring-2 focus:ring-[#134169]/25 focus:border-[#134169] outline-none transition duration-200">
            </div>
        </div>

        <button type="submit"
            class="group w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-xl
                   bg-[#134169] text-white text-[15px] font-semibold tracking-wide
                   hover:bg-[#0e3354] focus:outline-none focus:ring-2 focus:ring-[#134169]/40 focus:ring-offset-2
                   active:scale-[0.99] shadow-sm hover:shadow-md transition duration-200">
            Send reset link
            <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7-7 7M21 12H3" />
            </svg>
        </button>
    </form>

    {{-- Retour --}}
    <div class="text-center mt-6">
        <a href="{{ route('login') }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-[#134169] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
            </svg>
            Back to login
        </a>
    </div>

</x-guest-layout>
