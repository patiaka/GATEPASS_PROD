<x-guest-layout>
    <!-- Logo -->
    <div class="text-center mb-4">
        {{-- <x-logo /> --}}
        <h4 class="mt-3 fw-bold text-primary">Set New Password 🔒</h4>
        <p class="text-muted mb-0">Please choose a strong password for your account</p>
    </div>

    <!-- Error Alert -->
    @error('email')
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="bx bx-error-circle fs-4 me-2"></i>
            <div>
                <strong>Error!</strong><br>
                {{ $message }}
            </div>
        </div>
    @enderror

    <!-- Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form id="formAuthentication" action="{{ route('password.store') }}" method="POST" class="grid gap-4">
        @csrf
        <!-- Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
		<input type="email" readonly name="email" value="{{ $request->email }}">

        <div>
            <x-input label="New Password" type="password" name="password" placeholder="Enter new password" required class="w-full"/>
        </div>
    
        <div>
            <x-input label="Confirm Password" type="password" name="password_confirmation" placeholder="Confirm your password" required class="w-full"/>
        </div>

        <!-- Password -->
        {{-- <div class="mb-3">
            <label class="form-label fw-semibold">New Password</label>
            <div class="input-group input-group-merge">
                <input type="password"
                       class="form-control"
                       name="password"
                       placeholder="Enter new password"
                       required>
                <span class="input-group-text">
                    <i class="bx bx-hide"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small" />
        </div> --}}

        <!-- Confirm Password -->
        {{-- <div class="mb-4">
            <label class="form-label fw-semibold">Confirm Password</label>
            <div class="input-group input-group-merge">
                <input type="password"
                       class="form-control"
                       name="password_confirmation"
                       placeholder="Confirm your password"
                       required>
                <span class="input-group-text">
                    <i class="bx bx-hide"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger small" />
        </div> --}}

        <!-- Submit Button -->
        <button type="submit"
        class="w-full fw-semibold py-2 text-white"
        style="background-color: #134169; border-radius: 10px;">
    Reset Password
</button>

        <!-- Back -->
        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none text-muted">
                <i class="bx bx-arrow-back"></i>
                Back to Login
            </a>
        </div>
    </form>
</x-guest-layout>