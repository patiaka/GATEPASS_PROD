@props(['name'=>'','label'=>'','required' =>true])
<div class="form-group">
    <label {{ $attributes->merge(['class' => 'form-label']) }}>{{ $label }}</label>
    <select name="{{ $name }}" {{ $attributes->merge(['class' => 'select']) }}
        @required($required) >
        <option selected disabled value="">Select</option>
        {{ $slot }}
    </select>
    <div {{ $attributes->merge(['class' => 'valid-feedback']) }} ></div>
    <div {{ $attributes->merge(['class' => 'invalid-feedback']) }}>This field is required.</div>
    <ul {{ $attributes->merge(['class' => 'text-sm text-danger space-y-1']) }}>
        @error($name)
        <li>{{ $message }}</li>
        @enderror
    </ul>
</div>