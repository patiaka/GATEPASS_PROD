@props(['name'=>'','label'=>'','required' =>true])
<label {{ $attributes->merge(['class' => 'form-label']) }}>{{ $label }}</label>
<select name="{{ $name }}" {{ $attributes->merge(['class' => 'select2 form-select']) }}
    @required($required) data-allow-clear="true" >
    {{ $slot }}
</select>
<div {{ $attributes->merge(['class' => 'valid-feedback']) }} ></div>
<div {{ $attributes->merge(['class' => 'invalid-feedback']) }}>Ce champ est obligatoire.</div>
<ul {{ $attributes->merge(['class' => 'text-sm text-danger space-y-1']) }}>
    @error($name)
    <li>{{ $message }}</li>
    @enderror
</ul>