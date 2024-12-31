<x-guest-layout>
    <!-- Logo -->
    <x-logo />
    <!-- /Logo -->
    <h4 class="mb-2">Nouveau mot de passe 🔒</h4>
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
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form id="formAuthentication" class="mb-3" action="{{ route('password.store') }}" method="post">
        @csrf
        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="mb-3 form-password-toggle">
            <label class="form-label" for="password">Nouveau mot de passe</label>
            <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password"
                    placeholder="Entrez votre mot de passe" aria-describedby="password" required />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="mb-3 form-password-toggle">
            <label class="form-label" for="confirm-password">Confirmé le mot de passe</label>
            <div class="input-group input-group-merge">
                <input type="password" id="confirm-password" class="form-control" name="confirm-password"
                    placeholder="Confirmé le mot de passe" aria-describedby="password" required />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <button type="submit" class="btn btn-primary d-grid w-100 mb-3">Valider</button>
        <div class="text-center">
            <a wire:navigate href="{{ route('login') }}">
                <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                Back to login
            </a>
        </div>
    </form>
</x-guest-layout>
