@props([
    'target' => 'save', // Action Livewire ciblée pour l'état de chargement
    'label' => 'Save',
    'loadingLabel' => 'Saving…',
])

<button type="submit" wire:target="{{ $target }}" wire:loading.attr="disabled"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center gap-2 min-w-[120px] px-4 py-2 rounded-md text-sm font-semibold
                    text-white bg-[#0e3a61] shadow-sm transition-colors
                    hover:bg-[#0c3252] focus:outline-none focus:ring-2 focus:ring-[#0e3a61]/40 focus:ring-offset-1
                    disabled:opacity-70 disabled:cursor-not-allowed',
    ]) }}>

    {{-- État normal --}}
    <span wire:loading.remove wire:target="{{ $target }}" class="inline-flex items-center gap-2">
        {{ $label }}
    </span>

    {{-- État chargement : spinner + libellé --}}
    <span wire:loading wire:target="{{ $target }}" class="inline-flex items-center gap-2">
        <svg class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25" stroke-width="4" />
            <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
        </svg>
        {{ $loadingLabel }}
    </span>
</button>
