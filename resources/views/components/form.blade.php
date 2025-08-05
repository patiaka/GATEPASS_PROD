@props(['type'=> '','url' => '','route' => ''])
@if($type === "update")
<h2 class="p-4 text-center">Formulaire de mise à jour</h2>
@endif
<form wire:submit='{{ $route }}'>

    {{ $slot }}
    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 mt-4 justify-center">
        @if($type === "update" || $type === "create")
        <a wire:navigate href="{{ $url }}" class="btn btn-error">
            Annuler
        </a>
        @else

        <button type="button" command="close" commandfor="dialog" wire:loading.attr="disabled"
            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto ms-2">Cancel</button>
        @endif
        <button type="submit" wire:target="{{ $route }}"
            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-500 sm:ml-3 sm:w-auto">
            <span wire:loading wire:target="{{ $route }}">
                <span class="iconify lucide--loader size-4"></span> Traitement...
            </span>
            Valider
        </button>

    </div>

</form>