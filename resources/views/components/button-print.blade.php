@props(['row'])
{{-- @can('print', $row) --}}
<a aria-label="Button" {{ $attributes->merge(['class' => 'btn btn-info ']) }}>
    <i class="bx bxs-printer"></i> Print
</a>
{{-- @endcan --}}
