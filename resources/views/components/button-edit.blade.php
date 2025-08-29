@props(['row' => ''])
@if ($row)
@can('update-request', $row)
<a wire:navigate aria-label="Button" {{ $attributes->merge(['class' => 'text-sm inline-flex border-2 rounded-md
    bg-[#0e3a61]
    hover:bg-slate-200 px-2 py-1 shadow-sm text-sm text-white']) }} >
    <i data-lucide="Pencil"></i>
</a>
@endcan
@else
<a wire:navigate aria-label="Button" {{ $attributes->merge(['class' => 'text-sm inline-flex border-2 rounded-md
    bg-[#0e3a61]
    hover:bg-slate-200 px-2 py-1 shadow-sm text-sm text-white']) }}>
    <i data-lucide="Pencil"></i>
</a>
@endif