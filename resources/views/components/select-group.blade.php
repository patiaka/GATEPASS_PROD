@props(['name'=>'','label'=>'','required' =>true])
<div class="form-group form-focus select-focus">
    <select name="{{ $name }}" {{ $attributes->merge(['class' => 'select floating']) }}
        @required($required)>
        <option selected disabled value="">Select</option>
        {{ $slot }}
    </select>
    <label {{ $attributes->merge(['class' => 'focus-label']) }}>{{ $label }}</label>
</div>
<div {{ $attributes->merge(['class' => 'valid-feedback']) }} ></div>
<div {{ $attributes->merge(['class' => 'invalid-feedback']) }}>This field is required.</div>
<ul {{ $attributes->merge(['class' => 'text-sm text-danger space-y-1']) }}>
    @error($name)
    <li>{{ $message }}</li>
    @enderror
</ul>