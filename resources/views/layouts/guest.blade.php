<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @vite(['resources/css/app.css'])

    {{-- Analytics (Umami) --}}
    <script defer src="https://umami.syama.resolute-ltd.com.au/script.js" data-website-id="0c6dabe3-9320-43ae-b7b9-2c587073ab90"></script>
</head>

<body class="min-h-screen flex flex-col gap-10 items-center justify-center bg-gray-100"
      style="background-image: url('{{ asset('assets/img/resolute.jpg') }}'); background-size: cover;">


    <div class="bg-white shadow-2xl rounded-2xl border p-8 max-w-2xl w-94">
        <div class="flex flex-col gap-4 mb-8">
            <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" class="w-36 mx-auto">
            <span class="flex h-0.5 w-28 bg-slate-100 mx-auto"></span>
            <h1 class="text-base font-bold text-center text-[#134169]">
                Gatepass Request<br>Management
            </h1>
        </div>


        {{ $slot }}
    </div>

    <p class="text-center text-xs text-white mt-4">&copy; 2026 Somisy - GPR Management App</p>

    <script>
        // Toasts (pages invitées) : animation d'entrée + fermeture auto.
        function gpDismissToast(t) {
            if (!t) return;
            t.classList.add('opacity-0', 'translate-x-4');
            setTimeout(function () { t.remove(); }, 320);
        }
        function gpInitToasts() {
            var toasts = document.querySelectorAll('#gp-toasts .gp-toast');
            toasts.forEach(function (t, i) {
                // Entrée
                requestAnimationFrame(function () {
                    t.classList.remove('opacity-0', 'translate-x-4');
                });
                // Fermeture auto (échelonnée)
                setTimeout(function () { gpDismissToast(t); }, 6000 + i * 400);
            });
        }
        document.addEventListener('DOMContentLoaded', gpInitToasts);

        // Affiche / masque un champ mot de passe (bouton x-password-eye).
        function gpTogglePw(btn) {
            var input = btn.parentElement.querySelector('input');
            if (!input) return;
            var isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.querySelector('.gp-eye').classList.toggle('hidden', isHidden);
            btn.querySelector('.gp-eye-off').classList.toggle('hidden', !isHidden);
        }
    </script>

    <script>
        // Anti double-soumission pour les formulaires natifs (login, mot de passe…).
        // L'événement 'submit' ne se déclenche qu'après validation HTML5 : on désactive
        // alors le(s) bouton(s) d'envoi pour bloquer tout second clic. La soumission
        // courante, elle, se poursuit normalement.
        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (!form || e.defaultPrevented) return;
            var isLivewire = Array.prototype.some.call(form.attributes, function (a) {
                return a.name.indexOf('wire:submit') === 0;
            });
            if (isLivewire) return; // Livewire gère déjà le verrouillage
            form.querySelectorAll('button[type=submit], input[type=submit]').forEach(function (b) {
                if (b.disabled) return;
                b.disabled = true;
                b.classList.add('opacity-70', 'cursor-not-allowed');
            });
        }, true);
    </script>

</body>

</html>
