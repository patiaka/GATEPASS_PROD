@props([
'title' => 'Modal Title',
'name' => 'default', // unique id for this modal
'maxWidth' => 'max-w-lg',
'closeOnBackdrop' => true,
])


<script>
    document.addEventListener('alpine:init', () => {
// Initialize once
if (!Alpine.store('modal')) {
Alpine.store('modal', {
modals: {},
open(name) { this.modals[name] = true },
close(name) { this.modals[name] = false },
isOpen(name) { return !!this.modals[name] }
})
}


// Bridge Livewire events -> Alpine store
window.addEventListener('open-modal', (e) => {
if (e.detail?.name) Alpine.store('modal').open(e.detail.name)
})
window.addEventListener('close-modal', (e) => {
if (e.detail?.name) Alpine.store('modal').close(e.detail.name)
})
})

//    document.addEventListener('close-modal', (e) => {
//         if (e.detail.name) {
//             Alpine.store('modal').close(e.detail.name)
//         }
//     })

</script>


<template x-teleport="body">
    <div x-data x-on:close-modal.window="if ($event.detail.name === {{ $name }}) $store.modal.close({{ $name }})">
        <div x-data x-show="$store.modal.isOpen('{{ $name }}')" x-cloak
            @keydown.escape.window="$store.modal.close('{{ $name }}')" @click.self="$store.modal.close('{{ $name }}')"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">


            <div class="bg-white rounded-xl shadow-xl w-full {{ $maxWidth }} p-6 relative"
                x-show="$store.modal.isOpen('{{ $name }}')" x-transition.opacity x-transition.scale.origin-top>


                <div class="mb-4 border-b pb-2 flex justify-between items-center">
                    <h2 class="text-lg font-semibold">{{ $title }}</h2>
                    <button type="button" @click="$store.modal.close('{{ $name }}')"
                        class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>

                <div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</template>