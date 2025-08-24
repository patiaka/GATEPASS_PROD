@props(['row'=>''])
@if ($row)
@can('show-request', $row)
<a aria-label="Button" {{ $attributes->merge(['class' => 'btn btn-success btn-icon']) }}>
    <i data-lucide="eye"></i>
    jhh
</a>
@endcan
@else
<a aria-label="Button" {{ $attributes->merge(['class' => 'btn btn-success btn-icon']) }}>
    <i class="EyeIcon"></i>
</a>
@endif
