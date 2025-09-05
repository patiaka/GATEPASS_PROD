<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Resident & Vehicle Off Site Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --brand: #0E3A61; }

        @page { size: A4; margin: 12mm 10mm; }

        body { font-size: 11px; color: #0b0f19; }

        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #cbd5e1; padding: 4px 6px; vertical-align: top; }
        thead th { background: #f1f5f9; }

        .section-title { background: var(--brand); color: #fff; font-weight: 700; padding: 4px 6px; }
        .brand-border { border-color: var(--brand); }
        .brand-text { color: var(--brand); }
        .brand-bg-light { background: #eef5fb; }
        .small { font-size: 10px; }
        .mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }

        /* Key-value tables: first/third label columns narrower */
        .kv td:first-child { width: 25%; font-weight: 600; }
        .kv td:nth-child(3) { width: 22%; font-weight: 600; }

        /* Remove borders helper */
        .no-border td, .no-border th { border: 0; }

        /* Avoid page breaks inside critical blocks */
        .avoid-break { page-break-inside: avoid; }
            @media print { html { zoom: .95; } }
        * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    </style>
</head>

<body class="leading-snug">
    {{-- Header with Title (keeps backend data as-is) --}}
    <div class="flex justify-between items-start mb-2">
        <div class="flex items-center gap-3">
            {{-- <img src="/assets/img/logo.jpg" alt="Logo" style="height: 48px; width: auto;" /> --}}
            <div>
                <div class="text-lg font-bold brand-text">Resident and Vehicle Off Site Form</div>
            </div>
        </div>
        <div class="text-right text-xs">
            <p>Page 1 of 1</p>
        </div>
    </div>

    {{-- Restriction Note --}}
    <div class="p-3 border brand-border brand-bg-light small mb-2">
        Syama Camp Residents are <strong>not permitted off site between 7pm and 6am</strong> without express permission of the General Manager.
    </div>

    {{-- Resident / Vehicle Info --}}
    <h2 class="section-title">Resident / Vehicle Info</h2>
    <table class="mb-2 text-xs">
        <tbody>
            <tr>
                <td>Somisy Vehicle</td>
                <td>{{ ($carRequest->somisy_car ?? false) ? 'Yes' : 'No' }}</td>
                <td>Camp Resident</td>
                <td>{{ ($carRequest->resident ?? false) ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <td>Expatriate</td>
                <td>{{ ($carRequest->expatriate ?? false) ? 'Yes' : 'No' }}</td>
                <td>Escort Level</td>
                <td>{{ $carRequest->escort_level ?? '—' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Vehicle and Driver --}}
    <h2 class="section-title">Vehicle and Driver</h2>
    <div class="avoid-break">
        <table class="kv mb-2">
            <tbody>
                <tr>
                    <td>Vehicle Type</td>
                    <td>{{ $carRequest->car_type ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Vehicle #</td>
                    <td>{{ $carRequest->car_number ?? '—' }}</td>
                    <td>Route</td>
                    <td>{{ $carRequest->route ?? '—' }}</td>
                </tr>
                @php
                    $driverRel = optional($carRequest->loadMissing('car_drivers'))->car_drivers ?? collect();
                    $driver = $driverRel->first();
                @endphp
                <tr>
                    <td>Driver Name</td>
                    <td>{{ $driver->name ?? ($carRequest->name ?? '—') }}</td>
                    <td>Phone</td>
                    <td>{{ $driver->contact ?? ($carRequest->contact ?? '—') }}</td>
                </tr>
                <tr>
                    <td>Licence(s)</td>
                    <td colspan="3">{{ $carRequest->licence ?? '—' }}</td>
                </tr>
            </tbody>
        </table>

        <h3 class="font-semibold text-sm mb-1">Conditions</h3>
        <div class="p-3 border brand-border small mb-2">
            I understand that as the driver of the above vehicle, I am fully responsible for the safety of the passengers and vehicle whilst driving. Should any details provided change, I will notify site as soon as possible. I agree to abide by all relevant laws applicable to driving in Mali and all relevant policies and procedures as issued by SOMISY in respect of driving company vehicles. I understand that should I not comply with the above conditions relating to off-site use of a company vehicle, then I may jeopardise future off-site use of a company vehicle for others and myself, and disciplinary action may be taken as a result of my actions.
            <div class="mt-4 text-sm">
                <p>Driver’s Signature ______________________________________</p>
                <p>Date: {{ $carRequest->created_at ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Journey --}}
    <h2 class="section-title">Journey</h2>
    <table class="kv mb-2">
        <tbody>
            <tr>
                <td>Date valid from</td>
                <td>{{ $carRequest->start ?? '—' }}</td>
                <td>Date Until</td>
                <td>{{ $carRequest->end ?? '—' }}</td>
            </tr>
            <tr>
                <td>Departure Time</td>
                <td>{{ $carRequest->depart_at ?? '—' }}</td>
                <td>Arrival Time</td>
                <td>{{ $carRequest->arrive_at ?? '—' }}</td>
            </tr>
            <tr>
                <td>Destination(s)</td>
                <td colspan="3">{{ $carRequest->destination ?? '—' }}</td>
            </tr>
            <tr>
                <td>Reason</td>
                <td colspan="3">{{ $carRequest->reason ?? '—' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Resident Details --}}
    <h2 class="section-title">Resident Details</h2>
    <table class="mb-2">
        <thead>
            <tr>
                <th>Names</th>
                <th>Phones</th>
            </tr>
        </thead>
        <tbody>
            @foreach(($carRequest->passengers ?? collect()) as $p)
                <tr>
                    <td>{{ $p->name ?? '—' }}</td>
                    <td>{{ $p->contact ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 p-3 border brand-border small avoid-break">
        Approval to be obtained from General Manager and relevant Department Manager or Contract Manager. If more than one resident, then the department head who owns the vehicle signs off. If no vehicle is used, then each resident completes their own form. Notify Security via <span class="mono">SecurityDutyOfficer@rml.com.au</span> 48 hours prior to departure for an escort. The Security Duty Officer will determine the level of escort required. Late notice may result in delays. Ref <span class="mono">SEC-SEC-PRO-0011</span> Vehicle and Resident Off Site Procedure and <span class="mono">PRO-E-467</span> Security Escort Procedure.
        <div class="mt-4 text-sm">
            <p>Resident Signature ______________________________________</p>
            <p>Date: {{ $carRequest->created_at ?? '—' }}</p>
        </div>
    </div>

    {{-- Approval --}}
    <h2 class="section-title">Approval</h2>
    <table class="mb-2">
        <thead>
            <tr>
                <th>Department Manager</th>
                <th>General Manager</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $carRequest->hod_comment ?? '—' }}</td>
                <td>{{ $carRequest->gm_comment ?? '—' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Security Use Only --}}
    <h2 class="section-title">Security Use Only - Duty Officer and Control Room Notification</h2>
    <table class="mb-2">
        <tbody>
            <tr>
                <td>Security Supervisor Name</td>
                <td>{{ $carRequest->security_supervisor_name ?? '' }}</td>
                <td>Escort</td>
                <td>{{ $carRequest->security_escort ?? '' }}</td>
            </tr>
            <tr>
                <td>DO</td>
                <td>{{ $carRequest->security_do ?? '' }}</td>
                <td>CR</td>
                <td>{{ $carRequest->security_cr ?? '' }}</td>
            </tr>
            <tr>
                <td>Phone</td>
                <td>{{ $carRequest->security_phone ?? '' }}</td>
                <td>GPS</td>
                <td>{{ $carRequest->security_gps ?? '' }}</td>
            </tr>
            <tr>
                <td>LV</td>
                <td>{{ $carRequest->security_lv ?? ($carRequest->car_type ?? '') }}</td>
                <td>Date</td>
                <td>{{ $carRequest->security_date ?? '' }}</td>
            </tr>
            <tr>
                <td>Time Out</td>
                <td>{{ $carRequest->security_time_out ?? '' }}</td>
                <td>Time In</td>
                <td>{{ $carRequest->security_time_in ?? '' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Footer Meta --}}
    <div class="border-t pt-2 text-xs flex justify-between mt-2">
        <div>
            Document Owner: Security Manager (SMY)
        </div>
        <div class="text-right">
            Document Number: SEC-SEC-FRM-0001 &nbsp; | &nbsp; Revision 1.02 &nbsp; | &nbsp; Date Published: 23/11/2017 &nbsp; | &nbsp; Next Review: 24 months
        </div>
    </div>
</body>

</html>
