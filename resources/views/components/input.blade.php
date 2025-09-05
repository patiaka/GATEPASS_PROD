@props([
'disabled' => false,
'required' => true,
'name' => '',
'type' => 'text',
'place' => '',
'label' => '',
'value' => '',
'sm' => false,
])
<label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
    {{ $label ?: $name }}
    @if($required)
    <span class="text-red-500">*</span>
    @endif
</label>
<input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" placeholder="Enter {{ $place }}" @if($disabled) disabled
    @endif @if($required) required @endif value="{{ old($name, $value) }}" {{ $attributes->merge(['class' => 'w-1/2
border border-[#0e3a61] bg-gray-50 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0e3a61]']) }}
>

@error($name)
<span class="text-red-600 text-sm">{{ $message }}</span>
@enderror