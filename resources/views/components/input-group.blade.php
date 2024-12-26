@props(['disabled' => false,'required'=>true, 'name' => '','type' => '', 'place' => '', 'label' => '', 'value' =>
false,'messages'])
    <div class="form-group form-focus">
        <input {{ $attributes->merge(['class' => 'form-control floating mb-3']) }} type="{{ $type }}" value="{{
            $value }}"
            placeholder="Entrer {{ $place }}" name="{{ $name }}" id="{{ $name }}" value="{{ $value ? $value : @old($name) }}" {{
            $disabled ? 'disabled' : '' }}
            @required($required)
            >
        <label {{ $attributes->merge(['class' => 'text-uppercase focus-label']) }}>
            @empty($label)
            {{ $name }}
            @else
            {{ $label }}
            @endempty
        </label>
    </div>

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
