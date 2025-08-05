@props(['link' => ""])

@if ($link)
<a href="{{ $link }}" wire:navigate class="btn btn-primary btn-sm">
    <span class="iconify lucide--plus size-4"></span>
    <span class="hidden sm:inline">Nouveau</span>
</a>
@else

<button command="show-modal" commandfor="dialog"
    class="rounded-md bg-gray-950/5 px-2.5 py-1.5 text-sm font-semibold text-gray-900 hover:bg-gray-950/10">
    <i data-lucide="plus"></i>
    Nouveau
</button>
@endif