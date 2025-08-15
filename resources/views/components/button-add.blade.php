@props(['link' => ""])

@if ($link)
<a href="{{ $link }}" wire:navigate class="btn btn-primary btn-sm">
    <span class="iconify lucide--plus size-4"></span>

</a>
@else

<button command="show-modal" commandfor="dialog"
    class="rounded-md bg-[#0e3a61] px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-blue-500 sm:ml-3 sm:w-auto">
    <i data-lucide="plus"></i>
    <span class="hidden sm:inline">New</span>
</button>
@endif