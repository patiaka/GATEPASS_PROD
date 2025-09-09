<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Gate Pass / Bon de Sortie</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Optional: Poppins font to match your style -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
        }

        @media print {
            @page {
                size: A4;
                margin: 14mm;
            }

            .no-print {
                display: none !important;
            }

            img {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .break-inside-avoid {
                break-inside: avoid;
            }
        }
    </style>
</head>

<body class="text-[12px] leading-relaxed text-gray-900">

    <!-- Header -->
    <header class="relative h-20 bg-[#0F3369] text-white overflow-hidden flex items-center">
        <!-- Logo left -->
        <div class="h-full w-44 flex items-center justify-start pl-3">
            {{-- <img src="{{ asset('assets/img/logo.jpg') }}" alt="Company Logo"
                class="h-full w-auto object-contain" /> --}}
        </div>
        <!-- Centered title -->
        <div class="absolute inset-0 flex items-center justify-center">
            <h1 class="text-xl font-bold tracking-wide text-center">GATE PASS / BON DE SORTIE</h1>
        </div>
    </header>

    <main class="p-4 md:p-6 space-y-6">

        <!-- Request Info -->
        <section class="break-inside-avoid">
            <h2 class="sr-only">Request Information</h2>
            <table class="w-full border-2 border-black border-collapse">
                <tbody>
                    <tr>
                        <th class="w-1/5 border-2 border-black bg-gray-100 p-2 text-left">Date</th>
                        <td class="border-2 border-black p-2">{{ $MaterialRequest->created_at }}</td>
                    </tr>
                    <tr>
                        <th class="w-1/5 border-2 border-black bg-gray-100 p-2 text-left">Name</th>
                        <td class="border-2 border-black p-2">{{ $MaterialRequest->user->name }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Material Items -->
        <section class="break-inside-avoid">
            <h2 class="sr-only">Material Items</h2>
            <table class="w-full border-2 border-black border-collapse">
                <thead>
                    <tr class="[&>th]:border-2 [&>th]:border-black [&>th]:bg-gray-100 [&>th]:p-2 text-left">
                        <th class="w-12">#</th>
                        <th>DESCRIPTION / DESIGNATION</th>
                        <th class="w-24">QUANTITY</th>
                        <th class="w-40">Serial Number</th>

                    </tr>
                </thead>
                <tbody>
                    <!-- Example row (duplicate/remove as needed) -->
                    @foreach ($MaterialRequest->loadMissing('material_request_items')->material_request_items
                    as $key => $row)
                    <tr class="[&>td]:border-2 [&>td]:border-black [&>td]:p-2 align-top">
                        <td>{{ $key }}</td>
                        <td>{{ $row->designation }}</td>
                        <td>{{ $row->quantity }}</td>
                        <td>{{ $row->serial_number }}</td>
                    </tr>
                    @endforeach

                    <!-- /Example rows -->
                </tbody>
            </table>
        </section>
        <!-- Signature Approvals -->
        <section class="break-inside-avoid">
            <h3 class="text-center font-semibold text-[#0F3369] text-base">AUTHORISED SIGNATURE APPROVALS</h3>
            <table class="w-full border-2 border-black border-collapse">
                <thead>
                    <tr class="[&>th]:border-2 [&>th]:border-black [&>th]:bg-gray-100 [&>th]:p-2 text-center">
                        <th>Company / Dept</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Signature</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Applicant -->
                    <tr class="[&>td]:border-2 [&>td]:border-black [&>td]:p-2 text-center">
                        <td>{{ $MaterialRequest->user->department->name }}</td>
                        <td>{{ $MaterialRequest->user->name }}</td>
                        <td>{{ $MaterialRequest->user->poste }}</td>
                        <td class="h-14">
                            <a href="#" class="btn-successs">
                                ✅ Approved
                            </a>
                        </td>
                    </tr>
                    <!-- HOD -->
                    <tr class="[&>td]:border-2 [&>td]:border-black [&>td]:p-2 text-center">
                        <td>{{ $MaterialRequest->user->department->name }}</td>
                        <td>{{ $MaterialRequest->hodApproval ? $MaterialRequest->hodApproval->department->name : '—' }}
                        </td>
                        <td>Head of Department</td>
                        <td class="h-14">
                            <x-request-status :status="$MaterialRequest->getStatusFor('hod')" />


                        </td>
                    </tr>
                    <!-- GM -->
                    <tr class="[&>td]:border-2 [&>td]:border-black [&>td]:p-2 text-center">
                        <td>{{ $MaterialRequest->user->department->name }}</td>
                        <td>{{ $MaterialRequest->gmApproval ? $MaterialRequest->gmApproval->department->name : '—' }}
                        </td>
                        <td>General Manager</td>
                        <td class="h-14">
                            <x-request-status :status="$MaterialRequest->getStatusFor('gm')" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Notes -->
        <section class="text-[11px] space-y-1">
            <p class="font-semibold">Notes:</p>
            <ol class="list-decimal pl-5 space-y-1">
                <li>
                    The date items will be removed from site. Up to seven days can be nominated for where multiple exit
                    and
                    re-entry required.
                </li>
                <li>
                    General Manager - Somisy, General Manager – Operations, or General Manager – Sustainability.
                </li>
            </ol>
        </section>

    </main>


</body>

</html>