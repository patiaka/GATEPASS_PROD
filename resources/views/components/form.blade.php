@props(['type' => '', 'route', 'action'])
{{-- @if ($type === 'update')
<h2 class="p-4 text-center text-2xl">Update form</h2>
@endif --}}
<form wire:submit='{{ $action }}' class="grid gap-4">
    {{ $slot }}
    <div class="flex justify-end gap-2">
        {{-- @if ($route)
            <a href="{{ route($route) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                Cancel
            </a>
        @else
            <button type="button" command="close" commandfor="dialog" wire:loading.attr="disabled"
                class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50">
                Cancel
            </button>
        @endif
        <button type="submit" wire:target="{{ $action }}"
            class="rounded-md bg-[#0e3a61] px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-slate-800 cursor-pointer">
            <span wire:loading wire:target="{{ $action }}">
                <span class="iconify lucide--loader size-4"></span> Processing...
            </span>
            Save
        </button> --}}

        <x-form-action cancel="{{ $route }}" target="{{ $action }}" />
    </div>


</form>