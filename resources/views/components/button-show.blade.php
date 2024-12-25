@props(['row'=>''])
@if ($row)
@can('view', $row)
<a aria-label="Button" {{ $attributes->merge(['class' => 'btn btn-success btn-icon']) }}>
    <i class="bx bx-show"></i></a>
@endcan
@else
<a aria-label="Button" {{ $attributes->merge(['class' => 'btn btn-success btn-icon']) }}>
    <i class="fa fa-eye"></i></a>
@endif
