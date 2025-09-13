<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Gate Pass / Bon de Sortie</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Optional: Custom font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        @media print {
            @page {
                size: A4;
                margin: 20mm;
            }

            .no-print {
                display: none !important;
            }

            .break-inside-avoid {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            img {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body class="text-[12px] text-gray-900 leading-relaxed">

    <!-- Header -->
    <header class="relative h-24 bg-[#0F3369] text-white flex items-center justify-center">
        <h1 class="text-xl font-bold uppercase tracking-wider text-center">
            Gate Pass / Bon de Sortie
        </h1>
    </header>

    <!-- Main Content -->
    <main class="p-6 space-y-8">

        <!-- Request Info -->
        <section class="break-inside-avoid">
            <table class="w-full table-fixed border border-black border-collapse">
                <tbody>
                    <tr>
                        <th class="w-1/4 bg-gray-100 border border-black text-left px-3 py-2">Date</th>
                        <td class="border border-black px-3 py-2">{{ $MaterialRequest->created_at }}</td>
                    </tr>
                    <tr>
                        <th class="bg-gray-100 border border-black text-left px-3 py-2">Name</th>
                        <td class="border border-black px-3 py-2">{{ $MaterialRequest->user->name }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Material Items -->
        <section class="break-inside-avoid">
            <table class="w-full border border-black border-collapse text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-black px-2 py-2 w-12 text-left">#</th>
                        <th class="border border-black px-2 py-2 text-left">Description / Designation</th>
                        <th class="border border-black px-2 py-2 w-24 text-center">Quantity</th>
                        <th class="border border-black px-2 py-2 w-40 text-left">Serial Number</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($MaterialRequest->loadMissing('material_request_items')->material_request_items as $key => $row)
                    <tr>
                        <td class="border border-black px-2 py-1 text-center">{{ $loop->iteration }}</td>
                        <td class="border border-black px-2 py-1">{{ $row->designation }}</td>
                        <td class="border border-black px-2 py-1 text-center">{{ $row->quantity }}</td>
                        <td class="border border-black px-2 py-1">{{ $row->serial_number }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <!-- FINAL BLOCK: Approvals + Notes -->
        <section class="break-inside-avoid space-y-4 pt-6">

            <!-- Approvals -->
            <div>
                <h3 class="text-base font-semibold text-center text-[#0F3369] uppercase mb-2">
                    Authorised Signature Approvals
                </h3>
                <table class="w-full border border-black border-collapse text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-black px-2 py-2 text-center">Department</th>
                            <th class="border border-black px-2 py-2 text-center">Name</th>
                            <th class="border border-black px-2 py-2 text-center">Position</th>
                            <th class="border border-black px-2 py-2 text-center">Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Applicant -->
                        <tr>
                            <td class="border border-black px-2 py-2 text-center">{{ $MaterialRequest->user->department->name }}</td>
                            <td class="border border-black px-2 py-2 text-center">{{ $MaterialRequest->user->name }}</td>
                            <td class="border border-black px-2 py-2 text-center">{{ $MaterialRequest->user->poste }}</td>
                            <td class="border border-black px-2 py-2 text-center">✅ Approved</td>
                        </tr>

                        <!-- HOD -->
                        <tr>
                            <td class="border border-black px-2 py-2 text-center">{{ $MaterialRequest->user->department->name }}</td>
                            <td class="border border-black px-2 py-2 text-center">
                                {{ $MaterialRequest->hodApproval ? $MaterialRequest->hodApproval->department->name : '—' }}
                            </td>
                            <td class="border border-black px-2 py-2 text-center">Head of Department</td>
                            <td class="border border-black px-2 py-2 text-center">
                                <x-request-status :status="$MaterialRequest->getStatusFor('hod')" />
                            </td>
                        </tr>

                        <!-- GM -->
                        <tr>
                            <td class="border border-black px-2 py-2 text-center">{{ $MaterialRequest->user->department->name }}</td>
                            <td class="border border-black px-2 py-2 text-center">
                                {{ $MaterialRequest->gmApproval ? $MaterialRequest->gmApproval->department->name : '—' }}
                            </td>
                            <td class="border border-black px-2 py-2 text-center">General Manager</td>
                            <td class="border border-black px-2 py-2 text-center">
                                <x-request-status :status="$MaterialRequest->getStatusFor('gm')" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Notes -->
            <div class="text-[11px]">
                <p class="font-semibold">Notes:</p>
                <ol class="list-decimal pl-5 space-y-1 mt-1">
                    <li>The date items will be removed from site. Up to seven days can be nominated for multiple exits and re-entries.</li>
                    <li>Approval is required from a General Manager depending on the operations (Somisy, Operations, or Sustainability).</li>
                </ol>
            </div>

        </section>
    </main>

</body>
</html>
