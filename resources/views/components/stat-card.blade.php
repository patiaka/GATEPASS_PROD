@props([
    'href' => '#',
    'label' => '',
    'sublabel' => '',
    'value' => 0,
    'type' => 'all', // all | movements | approved | pending | rejected
])

@php
    // Icône toujours entourée de bleu marque ; la couleur de l'icône porte le statut.
    $tones = [
        'all' => 'text-white',
        'movements' => 'text-white',
        'approved' => 'text-emerald-300',
        'pending' => 'text-amber-300',
        'rejected' => 'text-rose-300',
    ];
    $icons = [
        'all' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>',
        'movements' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>',
        'approved' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>',
        'pending' => '<circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5V12l3 2"/>',
        'rejected' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>',
    ];
    $tx = $tones[$type] ?? 'text-white';
    $icon = $icons[$type] ?? $icons['all'];
@endphp

<a wire:navigate href="{{ $href }}"
    {{ $attributes->merge(['class' => 'group relative p-4 flex items-center justify-between gap-3 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:-translate-y-0.5 hover:border-[#134169]/30 transition-all duration-200']) }}>
    <div class="flex items-center gap-3 min-w-0">
        <span class="flex items-center justify-center h-11 w-11 rounded-full bg-[#134169] {{ $tx }} shrink-0 shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
        </span>
        <div class="min-w-0">
            <h3 class="text-sm font-semibold text-slate-700 leading-tight truncate uppercase tracking-wide">{{ $label }}</h3>
            <p class="text-[11px] text-slate-400 truncate">{{ $sublabel }}</p>
        </div>
    </div>
    <span class="text-2xl font-semibold text-slate-500 tabular-nums">{{ $value }}</span>
</a>
