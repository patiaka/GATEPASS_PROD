@props(['row'])
@can('download', $row)
<a aria-label="Button" {{ $attributes->merge(['class' => 'btn btn-info rounded-pill btn-icon']) }}>
    <i class="bx bxs-download"></i>
</a>
@endcan