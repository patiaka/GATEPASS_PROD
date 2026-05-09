@props([
'disabled' => false,
'required' => false,
'name' => '',
'place' => '',
'label' => '',
])


<label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
    {{ $label }}
</label>

<select
    id="{{ $name }}"
    {{ $attributes->merge([
        'class' => '
            w-full
            rounded-md
            border border-gray-300
            bg-white
            px-3 py-2
            text-sm text-gray-700
            shadow-sm
            transition duration-200 ease-in-out
            focus:outline-none
            focus:ring-2 focus:ring-blue-500/40
            focus:border-blue-500
            hover:border-gray-400
        '
    ]) }}
    @if($disabled) disabled @endif
    @if($required) required @endif
>
    <option value="" selected class="text-gray-400">
        Select {{ $label }}
    </option>

    {{ $slot }}
</select>