<!DOCTYPE html>
<html lang="fr" data-turbo-track="reload">

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
            html.nav-mini #sidebar .mt-auto { position: relative; }

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
            html.nav-mini #sidebar .mt-auto details:hover > ul { top: auto; bottom: 0; }

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

<body class="relative text-slate-600">
    <div class="xl:hidden flex items-center justify-between p-4 bg-white shadow">
        <button id="sidebarToggle" type="button" onclick="document.documentElement.classList.toggle('nav-open')"
            class="text-[#0e3a61] text-2xl focus:outline-none" aria-label="Open menu">
            ☰
        </button>
    </div>
    <div class="flex w-full h-screen bg-slate-100">
        @include('layouts.sidebar')


        <div class="main flex flex-col flex-1">
            @include('layouts.header')
            <div class="content flex-1 p-4 sm:p-6 lg:p-8 bg-slate-50 overflow-y-auto overflow-x-hidden">

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
        }
    </script>


</body>

</html>
