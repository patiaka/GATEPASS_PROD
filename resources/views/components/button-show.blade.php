@props(['row'=>''])
@if ($row)
@can('show-request', $row)

<a aria-label="Button" {{ $attributes->merge(['class' => 'inline-flex border-2 rounded-md bg-[#0e3a61]
    hover:bg-slate-200 px-2 py-1 shadow-sm text-sm text-white']) }} >
    <i data-lucide="eye"></i>
</a>
@endcan
@else
<a aria-label="Button" {{ $attributes->merge(['class' => 'inline-flex border-2 rounded-md bg-[#0e3a61]
    hover:bg-slate-200 px-2 py-1 shadow-sm text-sm text-white']) }} >
    <i data-lucide="eye"></i>
</a>
@endif
