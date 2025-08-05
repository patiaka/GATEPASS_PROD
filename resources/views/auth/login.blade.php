<x-guest-layout>
    <!-- Logo -->
    <x-logo />
    <h4 class="mb-2">Welcome! 👋</h4>
    <p class="mb-4">Please sign in to continue.</p>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form id="formAuthentication" class="mb-3" method="POST" id="formAuthentication" class="mb-3"
        action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mb-3 form-password-toggle">
            <div class="d-flex justify-content-between">
                @if (Route::has('password.request'))
                <label class="form-label" for="password">Password</label>
                <a href="{{ route('password.request') }}">
                    <small>Forgot your password?</small>
                </a>
                @endif
            </div>
            <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
        </div>
        <div class="mb-3">
            <div class="form-check">
                <label for="remember-me" class="form-check-label">
                    <input id="remember-me" type="checkbox" class="form-check-input" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>
        </div>
        <div class="mb-3">
            <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
        </div>
    </form>

</x-guest-layout>