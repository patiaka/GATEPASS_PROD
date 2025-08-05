@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full border border-gray-300 bg-gray-50
rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500']) !!}>