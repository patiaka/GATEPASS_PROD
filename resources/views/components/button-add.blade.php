@props(['link' => ''])

@php
$baseClasses = "inline-flex items-center gap-2 rounded-md bg-[#0e3a61] px-4 py-2 text-sm font-semibold text-white
shadow-sm transition-colors duration-150 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 sm:ml-3
sm:w-auto";
@endphp

@if ($link)
<a href="{{ $link }}" class="{{ $baseClasses }}">
    <i data-lucide="plus" class="w-4 h-4"></i>
    <span class="hidden sm:inline">New</span>
</a>
@else
<button type="button" command="show-modal" commandfor="dialog" class="{{ $baseClasses }}">
    <i data-lucide="plus" class="w-4 h-4"></i>
    <span class="hidden sm:inline">New</span>
</button>
@endif