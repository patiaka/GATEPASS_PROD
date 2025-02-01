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
    <div class="container-fluid invoice-print">
        <h3 class="text-center mb-2">Resident & Vehicle Off Site Travel Approval</h3>
        <div class="mb-2 d-flex justify-content-between align-items-center">
            <div>
                <p class="mb-1"><strong>Document No:</strong> {{ $carRequest->reference }}</p>
                <p class="mb-1"><strong>Title:</strong> Resident & Vehicle Off-Site Travel Approval</p>
                <p class="mb-0"><strong>Revision:</strong> 2.0</p>
                <p><strong>Date:</strong> {{ $carRequest->created_at }}</p>
            </div>
            <div>
                <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" class="img-fluid"
                    style="max-width: 100px; height: auto;">
            </div>
        </div>

        <p class="text-start mb-1"><strong>Off-site travel for all Syama camp residents and SOMISY vehicles is
                restricted to essential business purposes only</p>

        <table class="table table-bordered mb-2 table-sm">
            <thead>
                <tr>
                    <th colspan="4" class="text-center">SOMISY VEHICLE</th>
                    <th colspan="3" class="text-center">CAMP RESIDENT</th>
                    <th colspan="4" class="text-center">EXPATRIATE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" class="text-center">{{ $carRequest->somisy_car }}</td>
                    <td colspan="3" class="text-center">{{ $carRequest->resident }}</td>
                    <td colspan="4" class="text-center">{{ $carRequest->expatriate }}</td>
                </tr>

                <tr>
                    <td colspan="3">Licence(s)</td>
                    <td class="text-center">{{ $carRequest->licence }}</td>
                </tr>
                <tr>
                    <td colspan="3">Vehicle Type</td>
                    <td class="text-center">{{ $carRequest->car_type }}</td>
                    <td colspan="2">Vehicle #</td>
                    <td colspan="4" class="text-center">{{ $carRequest->car_number }}</td>
                </tr>
                <tr>
                    <td colspan="3">Dept/Company</td>
                    <td colspan="5" class="text-center">UG Mining</td>
                </tr>
            </tbody>
        </table>
        <h5 class="mb-1">VEHICLE AND DRIVER</h5>
        <table class="table table-bordered mb-2 table-sm">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($carRequest->car_drivers as $row)
                <tr>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->contact }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{-- <h6 class="mb-1">Conditions:</h6>
        <ul class="mb-2" style="
        font-size: smaller;
    ">
            <li>Recurring trips will be approved for a maximum of one business week in advance (Tuesday -- Wednesday)
            </li>
            <li>If more than one resident, HOD who owns the vehicle signs off</li>
            <li>If no site vehicle is used, each resident completes their own form</li>
            <li>Licence must be carried by driver and all local road rules obeyed</li>
            <li>The driver is responsible for the security of the vehicle at all times</li>
        </ul> --}}

        <table class="table table-bordered mb-2 table-sm">
            <tbody>
                <tr>
                    <td>Driver signature</td>
                    <td>Date: {{ now()->format('d/m/Y') }}</td>
                </tr>
            </tbody>
        </table>

        <table class="table table-bordered mb-2 table-sm">
            <tbody>
                <tr>
                    <td>Date Valid From</td>
                    <td>{{ $carRequest->start_format }}</td>
                    <td>Date Until</td>
                    <td>{{ $carRequest->end_format }}</td>
                </tr>
                <tr>
                    <td>Departure Time</td>
                    <td>{{ $carRequest->depart_at }}</td>
                    <td>Arrival Time</td>
                    <td>{{ $carRequest->arrive_at }}</td>
                </tr>
                <tr>
                    <td>Destination(s)</td>
                    <td colspan="3">{{ $carRequest->destination }}</td>
                </tr>
                <tr>
                    <td>Justification</td>
                    <td colspan="3">{{ $carRequest->justification }}</td>
                </tr>
            </tbody>
        </table>

        <h5 class="mb-1">RESIDENT PASSENGER DETAILS</h5>
        <table class="table table-bordered mb-2 table-sm">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Exp (Y/N)</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carRequest->passengers as $row)
                <tr>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->contact }}</td>
                    <td>{{ $row->phone }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>

        <h5 class="mb-1">APPROVAL</h5>
        <table class="table table-bordered mb-2 table-sm">
            <tbody>
                <tr>
                    <td>Approval Department Manager</td>
                    <td>Approval General Manager</td>
                </tr>
                <tr>
                    <td>Name: {{ $carRequest->hodApproval->name ?? 'N/A' }}</td>
                    <td>Name: {{ $carRequest->gmApproval->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Appointment: Approved</td>
                    <td>Appointment: Approved</td>
                </tr>
                <tr>
                    <td>Signature:</td>
                    <td>Signature:</td>
                </tr>
            </tbody>
        </table>
        <h6 class="mb-1">Conditions:</h6>
        <ul class="mb-2" style="
        font-size: smaller;
    ">
            <li>Recurring trips will be approved for a maximum of one business week in advance (Tuesday -- Wednesday)
            </li>
            <li>If more than one resident, HOD who owns the vehicle signs off</li>
            <li>If no site vehicle is used, each resident completes their own form</li>
            <li>Licence must be carried by driver and all local road rules obeyed</li>
            <li>The driver is responsible for the security of the vehicle at all times</li>
        </ul>
        <h5 class="mb-1">SECURITY USE ONLY - DUTY OFFICER AND CONTROL ROOM NOTIFICATION</h5>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Security Supervisor Name</th>
                    <th>Escort</th>
                    <th>Phone</th>
                    <th>GPS</th>
                    <th>LV</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Date</td>
                    <td>Time Out</td>
                    <td>Time In</td>
                </tr>
            </tbody>
        </table>
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