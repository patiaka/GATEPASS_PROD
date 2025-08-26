<x-guest-layout>
    <!-- Logo -->
    <x-logo />
    <!-- /Logo -->
    <h4 class=" mb-2">Password Change Required 🔒</h4>
    <p class="text-sm mb-2">
        On your first login, please change your password for security reasons.
        Follow the criteria below and submit the form. Your account will then be accessible with the new password.
    </p>
    <h6 class="text-sm mb-2">
        Note: Use at least eight (8) characters, mixing uppercase, lowercase, numbers, and special characters.
    </h6>

    @error('email')
    <div class="alert alert-danger d-flex" role="alert">
        <div class="d-flex flex-column ps-1">
            <h6 class="alert-heading d-flex align-items-center mb-1">Error!!</h6>
            <span>{{ $message }}</span>
        </div>
    </div>
    @enderror
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form class="mb-3" action="{{ route('change_password') }}" method="post">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <div class="mb-3 form-password-toggle">
            <label class="form-label" for="password">New password</label>
            <div class="input-group input-group-merge">
                <input type="password" id="password"
                    class="text-gray-900 bg-gray-50 rounded-lg text-sm block w-full p-2.5 border border-gray-300 focus:z-10 focus:ring-blue-500 focus:border-blue-500"
                    name="password" placeholder="Enter your password" aria-describedby="password" required />

            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="mb-3 form-password-toggle">
            <label class="form-label" for="password_confirmation">Confirm new password</label>
            <div class="input-group input-group-merge">
                <input type="password" id="password_confirmation"
                    class="text-gray-900 bg-gray-50 rounded-lg text-sm block w-full p-2.5 border border-gray-300 focus:z-10 focus:ring-blue-500 focus:border-blue-500"
                    name="password_confirmation" placeholder="Confirm new password" aria-describedby="password"
                    required />

            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <button
            class="w-full bg-[#134169] text-white font-semibold py-2 px-4 mt-8 rounded-lg hover:bg-[#0e3354] transition duration-300"
            type="submit">
            valide
        </button>
        <div class="text-center mt-2">
            <a wire:navigate href="{{ route('login') }}">
                Back to login
            </a>
        </div>
    </form>
</x-guest-layout>
