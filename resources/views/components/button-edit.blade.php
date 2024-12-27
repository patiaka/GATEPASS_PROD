@props(['row' => ''])
@if ($row)
@can('update', $row)
<a aria-label="Button" {{ $attributes->merge(['class' => 'btn rounded-pill btn-icon btn-primary']) }}>
    <span class="fa"></span>
</a>
@endcan
@else
<a aria-label="Button" {{ $attributes->merge(['class' => 'btn btn-success']) }}>
    <i class="fa fa-edit"></i>
</a>
@endif