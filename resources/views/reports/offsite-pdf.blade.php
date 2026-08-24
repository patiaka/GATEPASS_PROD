{{--
    Rapport PDF « Offsite Records ».

    Rendu par Browsershot (Chrome headless) : aucun CSS/JS externe, aucune police
    distante, tous les graphiques sont des SVG générés côté serveur
    (App\Support\SvgChart). Le logo est encodé en base64.
--}}
@php
    $logoPath = public_path('assets/img/logo.jpg');
    $keys = array_keys($sources);
    $single = count($keys) === 1;
    $pct = fn (int $value, int $total) => $total > 0 ? round($value / $total * 100, 1) : 0.0;
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8" />
    <title>{{ __('Offsite Records report') }}</title>
    <style>
        :root {
            --brand: #134169;
            --vehicle: #134169;
            --material: #059669;
            --ink: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
            --soft: #f8fafc;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        @page {
            size: A4 portrait;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: var(--ink);
            line-height: 1.4;
        }

        /* ── En-tête ─────────────────────────────────────────────── */
        .head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--brand);
        }

        .head img {
            height: 42px;
            width: auto;
        }

        .head .title {
            font-size: 17px;
            font-weight: 700;
            color: var(--brand);
            line-height: 1.15;
        }

        .head .sub {
            font-size: 9.5px;
            color: var(--muted);
        }

        .head .meta {
            margin-left: auto;
            text-align: right;
            font-size: 8.5px;
            color: var(--muted);
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin: 8px 0 12px;
        }

        .chip {
            border: 1px solid var(--line);
            background: var(--soft);
            border-radius: 999px;
            padding: 2px 9px;
            font-size: 8.5px;
            color: #334155;
        }

        .chip b {
            color: var(--brand);
        }

        /* ── Sections ────────────────────────────────────────────── */
        h2.section {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--brand);
            margin: 14px 0 7px;
            padding-bottom: 3px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: baseline;
            gap: 8px;
        }

        h2.section span {
            font-size: 8.5px;
            font-weight: 400;
            color: var(--muted);
            margin-left: auto;
        }

        .block {
            page-break-inside: avoid;
        }

        .page-break {
            page-break-before: always;
        }

        .row {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .col {
            flex: 1;
            min-width: 0;
        }

        /* ── Cartes d'indicateurs ────────────────────────────────── */
        .kpis {
            display: flex;
            gap: 8px;
        }

        .kpi {
            flex: 1;
            border: 1px solid var(--line);
            border-radius: 7px;
            padding: 8px 10px;
            background: #fff;
            border-top: 3px solid var(--brand);
        }

        .kpi .value {
            font-size: 20px;
            font-weight: 700;
            line-height: 1.1;
        }

        .kpi .label {
            font-size: 8.5px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .kpi .split {
            margin-top: 5px;
            padding-top: 4px;
            border-top: 1px dashed var(--line);
            font-size: 8.5px;
            color: #475569;
            display: flex;
            gap: 8px;
        }

        .dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 2px;
            margin-right: 3px;
            vertical-align: middle;
        }

        /* ── Tableaux ────────────────────────────────────────────── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
        }

        thead th {
            background: var(--brand);
            color: #fff;
            font-weight: 600;
            text-align: right;
            padding: 5px 7px;
            font-size: 9px;
        }

        thead th:first-child {
            text-align: left;
            border-radius: 4px 0 0 0;
        }

        thead th:last-child {
            border-radius: 0 4px 0 0;
        }

        tbody td {
            border-bottom: 1px solid var(--line);
            padding: 4px 7px;
            text-align: right;
        }

        tbody td:first-child {
            text-align: left;
            font-weight: 600;
            color: #1e293b;
        }

        tbody tr:nth-child(even) td {
            background: #fbfdff;
        }

        tfoot td {
            padding: 5px 7px;
            text-align: right;
            font-weight: 700;
            background: #eef4f9;
            color: var(--brand);
            border-top: 1.5px solid var(--brand);
        }

        tfoot td:first-child {
            text-align: left;
        }

        .share {
            display: flex;
            align-items: center;
            gap: 5px;
            justify-content: flex-end;
        }

        .share .track {
            width: 52px;
            height: 5px;
            border-radius: 3px;
            background: #edf2f7;
            overflow: hidden;
        }

        .share .fill {
            height: 5px;
            background: var(--brand);
            border-radius: 3px;
        }

        .muted {
            color: var(--muted);
        }

        .card {
            border: 1px solid var(--line);
            border-radius: 7px;
            padding: 8px 10px;
            background: #fff;
        }

        .card .cap {
            font-size: 9px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 5px;
        }

        .note {
            font-size: 8.5px;
            color: var(--muted);
            margin-top: 4px;
        }

        .badge {
            display: inline-block;
            border-radius: 4px;
            padding: 1px 6px;
            font-size: 8.5px;
            font-weight: 600;
            color: #fff;
        }
    </style>
</head>

<body>

    {{-- ─────────────────────────── En-tête ─────────────────────────── --}}
    <div class="head">
        @if (is_file($logoPath))
            <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($logoPath)) }}" alt="Somisy" />
        @endif
        <div>
            <div class="title">{{ __('Offsite Records report') }}</div>
            <div class="sub">{{ __('Somisy — Gate Pass') }} · {{ $filters['scope'] }}</div>
        </div>
        <div class="meta">
            {{ __('Generated on') }} {{ $generatedAt->format('d/m/Y H:i') }}<br />
            {{ __('By') }} {{ $generatedBy }}
        </div>
    </div>

    <div class="chips">
        <span class="chip">{{ __('Period') }} · <b>{{ $filters['period'] }}</b></span>
        <span class="chip">{{ __('Scope') }} · <b>{{ $filters['scope'] }}</b></span>
        <span class="chip">{{ __('Department') }} · <b>{{ $filters['department'] }}</b></span>
        <span class="chip">{{ __('Gate') }} · <b>{{ $filters['gate'] }}</b></span>
    </div>

    {{-- ─────────────────────── Indicateurs clés ────────────────────── --}}
    <div class="kpis block">
        @php
            $tiles = [
                ['label' => __('Requests submitted'), 'key' => 'requests'],
                ['label' => __('Check-outs'), 'key' => 'exits'],
                ['label' => __('Check-ins'), 'key' => 'entries'],
                ['label' => __('Currently out'), 'key' => 'out'],
            ];
        @endphp
        @foreach ($tiles as $tile)
            <div class="kpi">
                <div class="value">{{ number_format($totals[$tile['key']], 0, ',', ' ') }}</div>
                <div class="label">{{ $tile['label'] }}</div>
                @if (! $single)
                    <div class="split">
                        @foreach ($sources as $key => $s)
                            <span>
                                <span class="dot" style="background: {{ $s['color'] }}"></span>
                                {{ __($s['label']) }} {{ number_format($stats[$key][$tile['key']], 0, ',', ' ') }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- ───────────── Demandes soumises par département ────────────── --}}
    <h2 class="section">
        {{ __('Requests submitted by department') }}
        <span>{{ $filters['period'] }} · {{ $filters['scope'] }}</span>
    </h2>

    <div class="row block">
        <div class="col" style="flex: 1.35">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('Department') }}</th>
                        @foreach ($sources as $s)
                            <th>{{ __($s['label']) }}</th>
                        @endforeach
                        <th>{{ __('Total') }}</th>
                        <th>{{ __('Share') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requestsByDept as $row)
                        <tr>
                            <td>{{ $row['label'] }}</td>
                            @foreach ($keys as $key)
                                <td>{{ number_format($row[$key], 0, ',', ' ') }}</td>
                            @endforeach
                            <td><b>{{ number_format($row['total'], 0, ',', ' ') }}</b></td>
                            <td>
                                <div class="share">
                                    <span class="muted">{{ $pct($row['total'], $totals['requests']) }}%</span>
                                    <span class="track">
                                        <span class="fill"
                                            style="width: {{ $pct($row['total'], $totals['requests']) }}%"></span>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($keys) + 3 }}" class="muted" style="text-align:center">
                                {{ __('No data for this filter.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($requestsByDept->isNotEmpty())
                    <tfoot>
                        <tr>
                            <td>{{ __('Total') }}</td>
                            @foreach ($keys as $key)
                                <td>{{ number_format($stats[$key]['requests'], 0, ',', ' ') }}</td>
                            @endforeach
                            <td>{{ number_format($totals['requests'], 0, ',', ' ') }}</td>
                            <td>100%</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
            @if ($topDept)
                <p class="note">
                    {{ __('Top department') }} : <b>{{ $topDept['label'] }}</b>
                    — {{ number_format($topDept['total'], 0, ',', ' ') }} {{ __('requests') }}
                    ({{ $pct($topDept['total'], $totals['requests']) }}%)
                </p>
            @endif
        </div>

        <div class="col card" style="flex: 1">
            <div class="cap">{{ $single ? __('Share by department') : __('Vehicle vs Material') }}</div>
            {!! $single ? $svg['deptDonut'] : $svg['typeSplit'] !!}
        </div>
    </div>

    <div class="card block" style="margin-top:10px">
        <div class="cap">{{ __('Requests submitted by department') }}</div>
        {!! $svg['requestsByDept'] !!}
    </div>

    {{-- ─────────────── Statuts + sociétés (page 2) ─────────────────── --}}
    <div class="page-break"></div>

    <h2 class="section">{{ __('Requests by status') }}<span>{{ $filters['period'] }}</span></h2>

    <table class="block">
        <thead>
            <tr>
                <th>{{ __('Type') }}</th>
                @foreach ($statusList as $status)
                    <th>{{ __($status) }}</th>
                @endforeach
                <th>{{ __('Total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sources as $key => $s)
                <tr>
                    <td>
                        <span class="badge" style="background: {{ $s['color'] }}">{{ __($s['label']) }}</span>
                    </td>
                    @foreach ($statusList as $status)
                        <td>{{ number_format($statuses[$key][$status] ?? 0, 0, ',', ' ') }}</td>
                    @endforeach
                    <td><b>{{ number_format($stats[$key]['requests'], 0, ',', ' ') }}</b></td>
                </tr>
            @endforeach
        </tbody>
        @if (! $single)
            <tfoot>
                <tr>
                    <td>{{ __('Total') }}</td>
                    @foreach ($statusList as $status)
                        <td>{{ number_format(collect($statuses)->sum(fn ($s) => $s[$status] ?? 0), 0, ',', ' ') }}</td>
                    @endforeach
                    <td>{{ number_format($totals['requests'], 0, ',', ' ') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <h2 class="section">{{ __('Top companies by requests') }}<span>{{ __('Top 10') }}</span></h2>
    <div class="card block">{!! $svg['requestsByCompany'] !!}</div>

    <h2 class="section">{{ __('Check-outs over time') }}<span>{{ __('Daily') }}</span></h2>
    <div class="card block">{!! $svg['overTime'] !!}</div>

    <h2 class="section">{{ __('Check-outs by department') }}<span>{{ $filters['scope'] }}</span></h2>
    <div class="card block">{!! $svg['exitsByDept'] !!}</div>

    {{-- ──────────────── Détail par type (page 3) ───────────────────── --}}
    <div class="page-break"></div>

    @if (isset($sources['vehicle']))
        <h2 class="section" style="color: var(--vehicle)">
            {{ __('Vehicle') }} — {{ __('detail') }}
            <span>{{ $filters['period'] }}</span>
        </h2>

        <div class="kpis block">
            <div class="kpi" style="border-top-color: var(--vehicle)">
                <div class="value">{{ number_format($stats['vehicle']['requests'], 0, ',', ' ') }}</div>
                <div class="label">{{ __('Requests submitted') }}</div>
            </div>
            <div class="kpi" style="border-top-color: var(--vehicle)">
                <div class="value">{{ number_format($stats['vehicle']['exits'], 0, ',', ' ') }}</div>
                <div class="label">{{ __('Check-outs') }}</div>
            </div>
            <div class="kpi" style="border-top-color: var(--vehicle)">
                <div class="value">{{ number_format($stats['vehicle']['entries'], 0, ',', ' ') }}</div>
                <div class="label">{{ __('Check-ins') }}</div>
            </div>
            <div class="kpi" style="border-top-color: var(--vehicle)">
                <div class="value">{{ number_format($distinctVehicles, 0, ',', ' ') }}</div>
                <div class="label">{{ __('Distinct vehicles') }}</div>
            </div>
        </div>

        <div class="card block" style="margin-top:10px">
            <div class="cap">{{ __('Top vehicles by check-outs') }}</div>
            {!! $svg['topVehicles'] !!}
        </div>
    @endif

    @if (isset($sources['material']))
        <h2 class="section" style="color: var(--material)">
            {{ __('Material') }} — {{ __('detail') }}
            <span>{{ $filters['period'] }}</span>
        </h2>

        <div class="kpis block">
            <div class="kpi" style="border-top-color: var(--material)">
                <div class="value">{{ number_format($stats['material']['requests'], 0, ',', ' ') }}</div>
                <div class="label">{{ __('Requests submitted') }}</div>
            </div>
            <div class="kpi" style="border-top-color: var(--material)">
                <div class="value">{{ number_format($stats['material']['exits'], 0, ',', ' ') }}</div>
                <div class="label">{{ __('Check-outs') }}</div>
            </div>
            <div class="kpi" style="border-top-color: var(--material)">
                <div class="value">{{ number_format($stats['material']['entries'], 0, ',', ' ') }}</div>
                <div class="label">{{ __('Check-ins') }}</div>
            </div>
            <div class="kpi" style="border-top-color: var(--material)">
                <div class="value">{{ number_format($materialQuantity, 0, ',', ' ') }}</div>
                <div class="label">{{ __('Items requested') }}</div>
            </div>
        </div>

        <div class="card block" style="margin-top:10px">
            <div class="cap">{{ __('Top items by quantity') }}</div>
            {!! $svg['topMaterials'] !!}
        </div>
    @endif

    <h2 class="section">{{ __('Top companies by check-outs') }}<span>{{ __('Top 10') }}</span></h2>
    <div class="card block">{!! $svg['exitsByCompany'] !!}</div>

    <p class="note" style="margin-top:14px">
        {{ __('Figures cover the selected period and filters. Check-outs and check-ins count gate movements; requests count submitted forms.') }}
    </p>

</body>

</html>
