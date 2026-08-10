@props(['link' => ''])

@php
$baseClasses = "inline-flex items-center gap-2 rounded-md bg-[#0e3a61] px-4 py-2 text-sm font-semibold text-white
shadow-sm transition-colors duration-150 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 sm:ml-3
sm:w-auto";
@endphp

@if ($link)
<a href="{{ $link }}" class="{{ $baseClasses }}">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
    </svg>
    <span class="hidden sm:inline">New</span>
</a>
@else
<button type="button" command="show-modal" commandfor="dialog" class="{{ $baseClasses }}">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
    </svg>
    <span class="hidden sm:inline">New</span>
</button>
@endif