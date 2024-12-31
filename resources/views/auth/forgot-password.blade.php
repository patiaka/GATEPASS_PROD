<x-guest-layout>
    <!-- Logo -->
    <x-logo />
    <!-- /Logo -->
    <h4 class="mb-2">Mot de passe oublié? 🔒</h4>
    <p class="mb-4">Entrez votre adresse e-mail et nous vous enverrons
        par e-mail un lien de réinitialisation de mot de passe qui vous permettra d'en choisir un
        nouveau.</p>
    @error('email')
    <div class="alert alert-danger d-flex" role="alert">
        <span class="badge badge-center rounded-pill bg-danger border-label-danger p-3 me-2"><i
                class="bx bx-store fs-6"></i></span>
        <div class="d-flex flex-column ps-1">
            <h6 class="alert-heading d-flex align-items-center mb-1">Error!!</h6>
            <span>{{ $message }}</span>
        </div>
    </div>
    @enderror
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form id="formAuthentication" class="mb-3" action="auth-reset-password-basic.html"
        action="{{ route('password.email') }}" method="post">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Entrez votre email" autofocus
                required />
        </div>
        <button class="btn btn-primary d-grid w-100">Valider</button>
        @if (Session::has('two_factor:user_id'))
        <div class="text-center text-muted mt-3">
            <a href="{{ route('2fa_verify_code') }}">Envoyez-moi un nouveau code</a>
        </div>
        @endif
    </form>
    <div class="text-center">
        <a wire:navigate href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
            <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
            Back to login
        </a>
    </div>
</x-guest-layout>
