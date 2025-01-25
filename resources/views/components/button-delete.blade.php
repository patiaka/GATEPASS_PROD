@props([
'row' => '',
'url' => '',
])


@if ($row)
@can('delete-request', $row)
<button type="button" {{ $attributes->merge(['class' => 'btn btn-danger btn-icon']) }}
    onclick="deleteConfirmation('{{ $url }}')"><i class="bx bx-trash-alt"></i></button>
@endcan
@else
<button type="button" {{ $attributes->merge(['class' => 'btn btn-danger btn-icon']) }}
    onclick="deleteConfirmation('{{ $url }}')"><i class="bx bx-trash-alt"></i></button>
@endif