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
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="relative text-slate-600">
    <div class="lg:hidden flex items-center justify-between p-4 bg-white shadow">
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
        // Délégation sur document : survit aux navigations SPA de Livewire.
        (function() {
            function sidebar() { return document.getElementById('sidebar'); }
            function overlay() { return document.getElementById('overlay'); }
            function open() { sidebar()?.classList.remove('-translate-x-full'); overlay()?.classList.remove('hidden'); }
            function close() { sidebar()?.classList.add('-translate-x-full'); overlay()?.classList.add('hidden'); }

            document.addEventListener('click', function(e) {
                if (e.target.closest('#sidebarToggle')) {
                    sidebar()?.classList.contains('-translate-x-full') ? open() : close();
                } else if (e.target.closest('#overlay')) {
                    close();
                } else if (e.target.closest('#sidebar a') && window.innerWidth < 1024) {
                    // Fermer le tiroir après un clic de navigation sur tablette/mobile
                    close();
                }
            });

            // Sécurité : à chaque navigation SPA, on repart tiroir fermé sur petit écran.
            document.addEventListener('livewire:navigated', function() {
                if (window.innerWidth < 1024) close();
            });
        })();
    </script>


</body>

</html>
