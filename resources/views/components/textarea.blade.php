@props(['value'=>'', 'label'=>'','place'=>'', 'required'=> true, 'name'=>''])

<div class="form-group">
    <label for="{{ $name }}" {{ $attributes->merge(['class' => 'text-uppercase']) }}>
        @empty($label)
        {{ $name }}
        @else
        {{ $label }}
        @endempty
    </label>
    <textarea @required($required) name="{{ $name }}" id="{{ $name }}" {{
        $attributes->merge(['class' => 'form-control']) }} rows="4" placeholder="{{ $place }}">{{ $value }}</textarea>
    <div {{ $attributes->merge(['class' => 'valid-feedback']) }} ></div>
    <div {{ $attributes->merge(['class' => 'invalid-feedback']) }}>Ce champ est obligatoire.</div>
    @if ($errors->get($name))
    <ul {{ $attributes->merge(['class' => 'text-sm text-danger space-y-1']) }}>
        @foreach ((array) $errors->get($name) as $message)
        <li>{{ $message }}</li>
        @endforeach
    </ul>
    @endif
</div>
