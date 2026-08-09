<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Offsite Records Report</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; color: #1e293b; font-size: 12px; margin: 0; }
        .head { border-bottom: 3px solid #134169; padding-bottom: 12px; margin-bottom: 16px; }
        .head h1 { color: #134169; font-size: 20px; margin: 0 0 4px; }
        .meta { color: #64748b; font-size: 11px; }
        .kpis { display: flex; gap: 10px; margin: 16px 0 20px; }
        .kpi { flex: 1; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px; }
        .kpi .v { font-size: 20px; font-weight: 700; color: #0f172a; }
        .kpi .l { font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: .04em; margin-top: 2px; }
        h2 { color: #134169; font-size: 13px; margin: 18px 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th { background: #134169; color: #fff; text-align: left; padding: 6px 10px; font-size: 11px; text-transform: uppercase; letter-spacing: .03em; }
        td { padding: 6px 10px; border-bottom: 1px solid #eef2f7; }
        tr:nth-child(even) td { background: #f8fafc; }
        td.num, th.num { text-align: right; width: 90px; }
        .empty { color: #94a3b8; font-style: italic; padding: 8px 0; }
        .foot { margin-top: 18px; border-top: 1px solid #e2e8f0; padding-top: 8px; color: #94a3b8; font-size: 10px; }
    </style>
</head>
<body>
    <div class="head">
        <h1>Offsite Records — Vehicle Exits Report</h1>
        <div class="meta">{{ $filters }}<br>Generated: {{ $generatedAt }}</div>
    </div>

    <div class="kpis">
        <div class="kpi"><div class="v">{{ number_format($exitsCount) }}</div><div class="l">Exits</div></div>
        <div class="kpi"><div class="v">{{ number_format($entriesCount) }}</div><div class="l">Entries</div></div>
        <div class="kpi"><div class="v">{{ number_format($currentlyOut) }}</div><div class="l">Currently out</div></div>
        <div class="kpi"><div class="v">{{ number_format($distinctVehicles) }}</div><div class="l">Distinct vehicles</div></div>
    </div>

    <h2>Top vehicles by exits</h2>
    <table>
        <thead><tr><th>Vehicle</th><th class="num">Exits</th></tr></thead>
        <tbody>
            @forelse ($topVehicles as $r)
                <tr><td>{{ $r->label }}</td><td class="num">{{ $r->total }}</td></tr>
            @empty
                <tr><td colspan="2" class="empty">No exit recorded for this filter.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Exits by department</h2>
    <table>
        <thead><tr><th>Department</th><th class="num">Exits</th></tr></thead>
        <tbody>
            @forelse ($byDepartment as $r)
                <tr><td>{{ $r->label }}</td><td class="num">{{ $r->total }}</td></tr>
            @empty
                <tr><td colspan="2" class="empty">No exit recorded for this filter.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Daily exits</h2>
    <table>
        <thead><tr><th>Date</th><th class="num">Exits</th></tr></thead>
        <tbody>
            @forelse ($overTime as $r)
                <tr><td>{{ \Illuminate\Support\Carbon::parse($r->d)->format('d-m-Y') }}</td><td class="num">{{ $r->total }}</td></tr>
            @empty
                <tr><td colspan="2" class="empty">No exit recorded for this period.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="foot">Gate Pass Management — Offsite Records report</div>
</body>
</html>
