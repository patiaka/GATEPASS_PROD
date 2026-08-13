@props([
    'field',
    'label' => '',
    'sort' => null,
    'dir' => 'asc',
    'align' => 'left',
])
@php $active = $sort === $field; @endphp
<th @class([
    'px-4 py-2 font-semibold select-none',
    'text-center' => $align === 'center',
])>
    <button type="button" wire:click="sortBy('{{ $field }}')"
        @class([
            'group/sort inline-flex items-center gap-1 transition hover:text-[#134169] focus:outline-none',
            'text-[#134169]' => $active,
            'justify-center w-full' => $align === 'center',
        ])>
        <span>{{ $label }}</span>
        @if ($active)
            <svg class="w-3 h-3 shrink-0 transition-transform {{ $dir === 'asc' ? 'rotate-180' : '' }}"
                fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        @else
            <svg class="w-3 h-3 shrink-0 text-slate-300 group-hover/sort:text-slate-400"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4M8 15l4 4 4-4" />
            </svg>
        @endif
    </button>
</th>
