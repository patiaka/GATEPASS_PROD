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

        /* ===== Sidebar réduite en rail d'icônes (grand écran uniquement) ===== */
        @media (min-width: 1280px) {
            #sidebar { transition: width .2s ease; }
            html.sidebar-mini #sidebar { width: 4.75rem; }

            /* Réduit & non survolé : on ne garde que les icônes */
            html.sidebar-mini #sidebar > a,
            html.sidebar-mini #sidebar h2,
            html.sidebar-mini #sidebar span,
            html.sidebar-mini #sidebar details > ul,
            html.sidebar-mini #sidebar summary > svg:last-of-type {
                display: none;
            }
            html.sidebar-mini #sidebar a,
            html.sidebar-mini #sidebar summary {
                justify-content: center;
                padding-left: .5rem;
                padding-right: .5rem;
            }
            html.sidebar-mini #sidebarCollapse svg { transform: rotate(180deg); }

            /* Survol : ré-expansion (pousse le contenu) pour accéder aux libellés & sous-menus */
            html.sidebar-mini #sidebar:hover { width: 18rem; }
            html.sidebar-mini #sidebar:hover > a,
            html.sidebar-mini #sidebar:hover h2,
            html.sidebar-mini #sidebar:hover span,
            html.sidebar-mini #sidebar:hover details[open] > ul,
            html.sidebar-mini #sidebar:hover summary > svg:last-of-type {
                display: revert;
            }
            html.sidebar-mini #sidebar:hover a,
            html.sidebar-mini #sidebar:hover summary {
                justify-content: flex-start;
                padding-left: .75rem;
                padding-right: .75rem;
            }
        }
    </style>

    {{-- Applique la préférence "sidebar réduite" avant le rendu (évite le flash) --}}
    <script>
        if (localStorage.getItem('gp-sidebar-mini') === '1') {
            document.documentElement.classList.add('sidebar-mini');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="relative text-slate-600">
    <div class="xl:hidden flex items-center justify-between p-4 bg-white shadow">
        <button id="sidebarToggle" class="text-[#0e3a61] text-2xl focus:outline-none" aria-label="Open menu">
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
        // IMPORTANT : garde sur window pour n'attacher les écouteurs QU'UNE SEULE FOIS.
        // Sans ça, wire:navigate ré-exécute ce script à chaque page et empile les
        // écouteurs -> le toggle se déclenche plusieurs fois et "refuse de s'ouvrir".
        if (!window.__gpSidebarInit) {
            window.__gpSidebarInit = true;

            const gpSidebar = () => document.getElementById('sidebar');
            const gpOverlay = () => document.getElementById('overlay');
            const gpIsDrawer = () => window.innerWidth < 1280;
            const gpOpen = () => { gpSidebar()?.classList.remove('-translate-x-full'); gpOverlay()?.classList.remove('hidden'); };
            const gpClose = () => { gpSidebar()?.classList.add('-translate-x-full'); gpOverlay()?.classList.add('hidden'); };

            // Délégation sur document : survit aux navigations SPA.
            document.addEventListener('click', function(e) {
                if (e.target.closest('#sidebarCollapse')) {
                    // Grand écran : réduire/étendre le rail d'icônes (préférence mémorisée)
                    const mini = document.documentElement.classList.toggle('sidebar-mini');
                    localStorage.setItem('gp-sidebar-mini', mini ? '1' : '0');
                    return;
                }
                if (e.target.closest('#sidebarToggle')) {
                    e.preventDefault();
                    gpSidebar()?.classList.contains('-translate-x-full') ? gpOpen() : gpClose();
                } else if (e.target.closest('#overlay')) {
                    gpClose();
                } else if (e.target.closest('#sidebar a') && gpIsDrawer()) {
                    gpClose(); // fermer le tiroir après un clic de navigation (tablette/mobile)
                }
            });

            // À chaque navigation SPA, on repart tiroir fermé sur petit écran.
            document.addEventListener('livewire:navigated', function() {
                if (gpIsDrawer()) gpClose();
            });
        }
    </script>


</body>

</html>
