@props(['disabled' => false,'required'=>true, 'name' => '', 'label' => '', 'value' =>
false,'messages'])

<label {{ $attributes->merge(['class' => 'text-uppercase form-label']) }}>
    @empty($label)
    {{ $name }}
    @else
    {{ $label }}
    @endempty
</label>
<input {{ $attributes->merge(['class' => 'form-control flatpickr-time']) }} type="text" value="{{
$value }}"
placeholder="HH:MM" name="{{ $name }}" id="flatpickr-time" value="{{ $value ? $value : @old($name) }}" {{
$disabled ? 'disabled' : '' }}
@required($required)
>
<div {{ $attributes->merge(['class' => 'valid-feedback']) }} ></div>
<div {{ $attributes->merge(['class' => 'invalid-feedback']) }}>Ce champ est obligatoire.</div>
@if ($errors->get($name))
<ul {{ $attributes->merge(['class' => 'text-sm text-danger space-y-1']) }}>
    @foreach ((array) $errors->get($name) as $message)
    <li>{{ $message }}</li>
    @endforeach
</ul>
@endif
{{ $slot }}