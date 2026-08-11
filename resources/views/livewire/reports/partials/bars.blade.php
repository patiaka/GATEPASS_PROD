{{-- Liste de barres horizontales (classement). Props: $rows, $color, $unit, $labelWidth, $empty --}}
@php $barsMax = collect($rows)->max('total') ?: 1; @endphp
@forelse ($rows as $row)
    <div class="flex items-center gap-3 py-1.5" title="{{ $row->label }} — {{ $row->total }} {{ $unit ?? '' }}">
        <span class="{{ $labelWidth ?? 'w-32' }} shrink-0 text-xs font-medium text-slate-600 truncate">{{ $row->label }}</span>
        <div class="flex-1 h-4 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-4 rounded-full" style="width: {{ max(4, round($row->total / $barsMax * 100)) }}%; background: {{ $color ?? '#134169' }};"></div>
        </div>
        <span class="w-8 text-right text-xs font-bold text-slate-700">{{ $row->total }}</span>
    </div>
@empty
    <p class="text-sm text-slate-400 italic py-6 text-center">{{ $empty ?? 'No data for this filter.' }}</p>
@endforelse
