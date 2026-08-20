<!DOCTYPE html>
<html lang="fr" data-turbo-track="reload" class="h-full overflow-hidden">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'Sidebar Somisy Template' }}</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--
    <link rel="stylesheet" href="{{ asset('build/assets/app-Dz6fu0Y1.css') }}"> --}}
    {{-- <script src="{{ asset('build/assets/app-Dz6fu0Y1.css') }}"></script> --}}
    <style>
        /* Pour cacher/afficher les sous-menus */
        .submenu {
            display: none;
        }

        details[open]>.submenu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding-left: 1rem;
            margin-top: 0.25rem;
            border-left: 2px solid #164e63;
            /* couleur bleu foncé */
        }

        /* Style pour l'item actif */
        .menu-item.active {
            background-color: #0e3a61a8;
            color: #e0e7ff;
            /* un bleu clair */
        }

        /* Curseur pointer sur les summaries */
        details>summary {
            list-style: none;
            cursor: pointer;
        }

        details>summary::-webkit-details-marker {
            display: none;
        }

        /* ===== Navigation latérale — 100% CSS, pilotée par des classes sur <html> ===== */
        #overlay { display: none; }

        /* Tablette / mobile : tiroir (caché par défaut, ouvert via .nav-open) */
        @media (max-width: 1279.98px) {
            #sidebar { position: fixed; transform: translateX(-100%); transition: transform .22s ease; }
            html.nav-open #sidebar { transform: translateX(0); }
            html.nav-open #overlay { display: block; }
        }

        /* Grand écran : sidebar fixe ; rail d'icônes via .nav-mini */
        @media (min-width: 1280px) {
            #sidebar { position: static; transform: none; transition: width .2s ease; }
            html.nav-mini #sidebar { width: 4.75rem !important; }
            html.nav-mini #sidebar > a:first-of-type,
            html.nav-mini #sidebar h2,
            html.nav-mini #sidebar span,
            html.nav-mini #sidebar details > ul,
            html.nav-mini #sidebar summary > svg:last-of-type { display: none; }
            html.nav-mini #sidebar a,
            html.nav-mini #sidebar summary { justify-content: center; padding-left: .5rem; padding-right: .5rem; }

            /* Garder l'emblème (2e bloc logo) : compact, centré, sans le texte */
            html.nav-mini #sidebar > a.logo-wrapper {
                background: transparent;
                border-color: transparent;
                box-shadow: none;
                justify-content: center;
                padding: .3rem;
            }
            html.nav-mini #sidebar > a.logo-wrapper > div:last-child { display: none; }
            html.nav-mini #sidebarCollapse svg { transform: rotate(180deg); }

            /* Sous-menus & profil : apparaissent en flyout à droite de l'icône au survol */
            html.nav-mini #sidebar .overflow-y-auto { overflow: visible; }
            html.nav-mini #sidebar li,
            html.nav-mini #sidebar .sidebar-footer { position: relative; }

            html.nav-mini #sidebar details:hover > ul {
                display: block !important;
                position: absolute;
                left: 100%;
                top: 0;
                width: 14rem;
                margin: 0;
                padding: .4rem;
                background: #0e3a61;
                border: 1px solid rgba(255, 255, 255, .14);
                border-radius: .6rem;
                box-shadow: 0 16px 50px rgba(0, 0, 0, .55);
                z-index: 70;
            }
            /* Profil (en bas) : le flyout s'ancre vers le haut */
            html.nav-mini #sidebar .sidebar-footer details:hover > ul { top: auto; bottom: 0; }

            /* Pied de profil en rail : avatar seul, centré ; texte + chevron masqués */
            html.nav-mini #sidebar .sidebar-footer .profile-meta { display: none; }
            html.nav-mini #sidebar .sidebar-footer summary { gap: 0; padding: .35rem; }

            html.nav-mini #sidebar details:hover > ul span { display: inline; }
            html.nav-mini #sidebar details:hover > ul a { justify-content: flex-start; padding-left: .6rem; padding-right: .6rem; }
        }
    </style>

    {{-- Applique la préférence "rail réduit" avant le rendu (évite le flash) --}}
    <script>
        if (localStorage.getItem('gp-sidebar-mini') === '1') {
            document.documentElement.classList.add('nav-mini');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="relative text-slate-600 h-screen h-[100dvh] flex flex-col overflow-hidden">
    <div class="xl:hidden shrink-0 flex items-center justify-between p-4 bg-white shadow">
        <button id="sidebarToggle" type="button" onclick="document.documentElement.classList.toggle('nav-open')"
            class="text-[#0e3a61] text-2xl focus:outline-none" aria-label="Open menu">
            ☰
        </button>
    </div>
    <div class="flex w-full flex-1 min-h-0 bg-slate-100">
        @include('layouts.sidebar')


        <div class="main flex flex-col flex-1 min-w-0 min-h-0">
            @include('layouts.header')
            <div class="content flex-1 min-h-0 p-4 sm:p-6 lg:p-8 bg-[#f4f6f9] overflow-y-auto overflow-x-hidden min-w-0">

                {{ $slot }}
            </div>
        </div>
    </div>
    <!-- Sidebar -->

    <!-- Content -->
    {{-- Lucide (icônes) est servi en local via Vite : voir resources/js/app.js --}}
    <script>
        // Le toggle (☰) et le rail (bouton réduire) sont des onclick inline pilotant des classes
        // sur <html> + du CSS -> déterministe, aucune lecture d'état, robuste au wire:navigate.
        // Ici, on ferme juste le tiroir après une navigation SPA (écouteur toujours correct).
        if (!window.__gpNavInit) {
            window.__gpNavInit = true;
            document.addEventListener('livewire:navigated', function () {
                document.documentElement.classList.remove('nav-open');
            });

            // Ferme les menus déroulants (profil <details data-autoclose> + "Create New")
            // quand on clique en dehors, ou avec la touche Échap.
            function gpCloseMenus(except) {
                document.querySelectorAll('details[data-autoclose][open]').forEach(function (d) {
                    if (d !== except && !d.contains(except)) d.removeAttribute('open');
                });
                var cb = document.getElementById('toggle-dropdown');
                if (cb && cb.checked) {
                    var wrap = cb.closest('.relative');
                    if (!wrap || !wrap.contains(except)) cb.checked = false;
                }
            }

            document.addEventListener('click', function (e) {
                gpCloseMenus(e.target);
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') gpCloseMenus(null);
            });
        }
    </script>

    {{-- Verrouillage par inactivité (utile pour un poste de guérite partagé) --}}
    @auth
        @php $gpIdleTimeout = (int) \App\Models\Setting::get('idle_timeout_minutes', 15); @endphp
        @if ($gpIdleTimeout > 0)
            <div id="gp-idle-modal" class="hidden fixed inset-0 z-[200] flex items-center justify-center bg-black/50 p-4">
                <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 text-center">
                    <div class="mx-auto mb-3 flex items-center justify-center w-12 h-12 rounded-full bg-amber-100 text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800">{{ __('Still there?') }}</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        {{ __('You will be signed out in') }}
                        <span id="gp-idle-countdown" class="font-semibold text-slate-700">60</span>
                        {{ __('seconds due to inactivity.') }}
                    </p>
                    <button id="gp-idle-stay" type="button"
                        class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-[#134169] text-white text-sm font-semibold hover:bg-[#0e3a61] transition">
                        {{ __('Stay signed in') }}
                    </button>
                </div>
            </div>
            <script>
                if (!window.__gpIdleInit) {
                    window.__gpIdleInit = true;
                    (function () {
                        var IDLE_MS = {{ $gpIdleTimeout }} * 60000;
                        var WARN_MS = Math.min(60000, Math.floor(IDLE_MS / 2));
                        var idleTimer, warnTimer, cdTimer;
                        var el = function (id) { return document.getElementById(id); };

                        function logout() {
                            var f = el('logout-form');
                            if (f) { f.submit(); } else { window.location.href = @json(route('logout')); }
                        }
                        function warn() {
                            var left = Math.floor(WARN_MS / 1000);
                            var m = el('gp-idle-modal'), c = el('gp-idle-countdown');
                            if (c) c.textContent = left;
                            if (m) m.classList.remove('hidden');
                            cdTimer = setInterval(function () {
                                left--;
                                var c2 = el('gp-idle-countdown');
                                if (c2) c2.textContent = left;
                                if (left <= 0) { clearInterval(cdTimer); logout(); }
                            }, 1000);
                            warnTimer = setTimeout(logout, WARN_MS);
                        }
                        function reset() {
                            clearTimeout(idleTimer); clearTimeout(warnTimer); clearInterval(cdTimer);
                            var m = el('gp-idle-modal');
                            if (m) m.classList.add('hidden');
                            idleTimer = setTimeout(warn, Math.max(0, IDLE_MS - WARN_MS));
                        }
                        ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart'].forEach(function (ev) {
                            document.addEventListener(ev, function () {
                                var m = el('gp-idle-modal');
                                if (m && !m.classList.contains('hidden')) return; // alerte affichée : clic explicite requis
                                reset();
                            }, true);
                        });
                        document.addEventListener('click', function (e) {
                            if (e.target.closest && e.target.closest('#gp-idle-stay')) reset();
                        });
                        reset();
                    })();
                }
            </script>
        @endif
    @endauth

</body>

</html>
