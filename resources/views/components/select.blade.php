@props([
'disabled' => false,
'required' => true,
'name' => '',
'place' => '',
'label' => '',
])


<label for="{{ $name }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $label
    }}</label>
<select id="{{ $name }}" {{ $attributes->merge(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm
    rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600
    dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500']) }}
    @if($disabled) disabled
    @endif @if($required) required @endif >
    <option value="" selected>select {{ $name }}</option>
    {{ $slot }}
</select>