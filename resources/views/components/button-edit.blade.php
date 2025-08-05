@props(['row' => ''])
@if ($row)
@can('update-request', $row)
<a aria-label="Button" {{ $attributes->merge(['class' => 'inline-flex border-2 rounded-md bg-slate-100
    hover:bg-slate-200 px-2 py-1 shadow-sm text-sm']) }}>
    <i data-lucide="Pencil"></i>
</a>
@endcan
@else
<a aria-label="Button" {{ $attributes->merge(['class' => 'inline-flex border-2 rounded-md bg-slate-100
    hover:bg-slate-200 px-2 py-1 shadow-sm text-sm']) }}>
    <i data-lucide="Pencil"></i>
</a>
@endif