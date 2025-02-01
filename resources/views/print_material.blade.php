<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/"
    data-template="vertical-menu-template-free" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <!-- Page CSS -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

</head>

<body>

    <div class="container-fluid invoice-print p-3">
        <h3 class="text-center mb-2">GATE PASS / BON DE SORTIE</h3>
        <div class="mb-2 d-flex justify-content-between align-items-center">
            <div>
                <p class="mb-1"><strong>Gate Pass No:</strong> {{ $materialRequest->reference }}</p>
                <p><strong>Date:</strong> {{ $materialRequest->created_at }}</p>
            </div>
            <div>
                <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" class="img-fluid"
                    style="max-width: 100px; height: auto;">
            </div>
        </div>
        <div class="gate-pass">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>DESIGNATION</th>
                        <th>QUANTITY</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materialRequest->material_request_items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->designation }}</td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h3 class="text-center">AUTHORISED SIGNATURE APPROVALS</h3>
            <div class="signature-table">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $materialRequest->isHodApproved() ? $materialRequest->hodApproval->compagnie->name :
                                'N/A' }}</td>
                            <td>{{ $materialRequest->isHodApproved() ? $materialRequest->hodApproval->department->name :
                                'N/A' }}</td>
                            <td>{{ $materialRequest->hod_approval_view() }}</td>
                            <td>{{ $materialRequest->isHodApproved() ? $materialRequest->hodApproval->poste : 'N/A' }}
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>{{ $materialRequest->isGmApproved() ? $materialRequest->gmApproval->compagnie->name :
                                'N/A' }}</td>
                            <td>{{ $materialRequest->isGmApproved() ? $materialRequest->gmApproval->department->name :
                                'N/A' }}</td>
                            <td>{{ $materialRequest->gm_approval_view() }}</td>
                            <td>{{ $materialRequest->isGmApproved() ? $materialRequest->gmApproval->poste : 'N/A' }}
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="notes">
                <p><strong>Notes:</strong></p>
                <ol>
                    <li>The date items will be removed from site. Up to seven days can be nominated for where multiple
                        exit and re-entry required.</li>
                    <li>General Manager - Somisy, General Manager -- Operations, or General Manager -- Sustainability.
                    </li>
                </ol>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        (function () {
        window.print();
    })();
    </script>

</body>

</html>