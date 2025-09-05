<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen flex flex-col gap-10 items-center justify-center bg-gray-100"
      style="background-image: url('{{ asset('assets/img/resolute.jpg') }}'); background-size: cover; background-position: center;">


    <div class="bg-white shadow-2xl rounded-2xl border p-8 max-w-2xl w-96">
        <div class="flex flex-col gap-4 mb-8">
            <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" class="w-36 mx-auto">
            <span class="flex h-0.5 w-28 bg-slate-100 mx-auto"></span>
            <h1 class="text-base font-bold text-center text-[#134169]">
                Gatepass Request<br>Management
            </h1>
        </div>


        {{ $slot }}
    </div>

    <p class="text-center text-xs text-white mt-4">&copy; 2025 Somisy - GPR Management App</p>

</body>

</html>
