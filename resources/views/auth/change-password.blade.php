<x-guest-layout>

    {{-- En-tête --}}
    <div class="flex items-center gap-3 mb-4">
        <span class="flex items-center justify-center w-11 h-11 shrink-0 rounded-full bg-[#134169]/10 text-[#134169] ring-1 ring-[#134169]/15">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
        </span>
        <div>
            <h1 class="text-lg font-bold text-[#134169] leading-tight">Password change required</h1>
            <p class="text-xs text-slate-500">First login — please set a new password to continue.</p>
        </div>
    </div>

    {{-- Bandeau d'info --}}
    <div class="rounded-lg bg-amber-50 border border-amber-200 p-3 mb-4">
        <p class="text-xs text-amber-800">
            For security reasons, choose a strong password. Once submitted, you'll be signed in automatically.
        </p>
    </div>

    {{-- Erreur (email introuvable, etc.) --}}
    @error('email')
        <div class="rounded-lg bg-rose-50 border border-rose-200 p-3 mb-4 flex items-start gap-2">
            <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
            </svg>
            <span class="text-xs text-rose-700">{{ $message }}</span>
        </div>
    @enderror

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form class="grid gap-4" action="{{ route('change_password') }}" method="post">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        {{-- New password --}}
        <div class="flex flex-col">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="password">New password</label>
            <div class="relative">
                <input type="password" id="password" name="password" placeholder="Enter your new password"
                    autocomplete="new-password" required
                    class="text-gray-900 bg-gray-50 rounded-lg text-sm block w-full p-2.5 pr-11 border border-gray-300 focus:z-10 focus:ring-blue-500 focus:border-blue-500">
                <x-password-eye />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirm new password --}}
        <div class="flex flex-col">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="password_confirmation">Confirm new password</label>
            <div class="relative">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Re-enter your new password"
                    autocomplete="new-password" required
                    class="text-gray-900 bg-gray-50 rounded-lg text-sm block w-full p-2.5 pr-11 border border-gray-300 focus:z-10 focus:ring-blue-500 focus:border-blue-500">
                <x-password-eye />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Exigences --}}
        <div class="rounded-lg bg-slate-50 border border-slate-200 p-3">
            <p class="text-xs font-semibold text-slate-600 mb-2">Password requirements</p>
            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-xs text-slate-500">
                @foreach (['At least 8 characters', 'Upper and lowercase letters', 'At least one number', 'At least one symbol'] as $req)
                    <li class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ $req }}
                    </li>
                @endforeach
            </ul>
        </div>

        <button type="submit"
            class="w-full bg-[#134169] text-white font-semibold py-2.5 px-4 rounded-lg hover:bg-[#0e3354] transition duration-300 focus:outline-none focus:ring-2 focus:ring-[#134169]/40">
            Set new password
        </button>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-blue-600">Back to login</a>
        </div>
    </form>
</x-guest-layout>
