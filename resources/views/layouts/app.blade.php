<!DOCTYPE html>
<html lang="fr" data-turbo-track="reload">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sidebar Somisy Template</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    @vite(['resources/css/app.css'])

</head>

<body class="relative text-slate-600">

    <div class="flex w-full h-screen bg-slate-100">
        @include('layouts.sidebar')


        <div class="main flex flex-col flex-1">
            @include('layouts.header')
            <div class="content flex-1 p-8 bg-slate-50 overflow-y-scroll">

                {{ $slot }}
            </div>
        </div>
    </div>
    <!-- Sidebar -->

    <!-- Content -->
    <!-- Development version -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- Production version -->
    {{-- <script src="https://unpkg.com/lucide@latest"></script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
    lucide.createIcons();
});

window.addEventListener("livewire:navigated", () => {

  lucide.createIcons();
});
    </script>
</body>

</html>