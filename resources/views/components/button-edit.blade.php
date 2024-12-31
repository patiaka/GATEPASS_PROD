@props(['row' => ''])
@if ($row)
@can('update', $row)
<a aria-label="Button" {{ $attributes->merge(['class' => 'btn rounded-pill btn-icon btn-primary']) }}>
    <span class="tf-icons bx bx-edit-alt"></span>
</a>
@endcan
@else
<a aria-label="Button" {{ $attributes->merge(['class' => 'btn rounded-pill btn-icon btn-primary']) }}>
    <span class="tf-icons bx bx-edit-alt"></span>
</a>
@endif