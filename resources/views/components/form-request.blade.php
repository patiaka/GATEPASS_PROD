@props(['type','model'])

@php
    $user = Auth::user();

    // La méthode d'approbation est déterminée par l'ÉTAPE de la demande
    // (next_approver_role), pas par le rôle de l'utilisateur — indispensable
    // pour un utilisateur multi-rôles (ex. HOD+GM) qui doit appeler la bonne
    // étape et ne pas rester bloqué sur approveByHod.
    $approveMethod = match ($model->next_approver_role) {
        \App\Enum\RoleEnum::HOD->value => 'approveByHod',
        \App\Enum\RoleEnum::DIRECTOR->value => 'approveByDirector',
        \App\Enum\RoleEnum::GM->value => 'approveByGm',
        default => null,
    };
@endphp

@if ($approveMethod)

{{-- @if ($canShowBtn) --}}
<button x-data @click="$dispatch('open-modal', { id: 'dialog-{{ $model->id }}' })"
    class="text-white bg-[#0e3a61] border border-[#0e3a61] px-3 py-1 rounded hover:bg-white hover:text-[#0e3a61]">
    Submit Response
</button>
{{-- @endif --}}

{{-- Modal téléporté dans le body --}}
<template x-teleport="body">
    <div x-data="{ open: false }"
        x-on:open-modal.window="if ($event.detail.id === 'dialog-{{ $model->id }}') open = true" x-show="open" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative" @click.outside="open = false">
            <!-- Header -->
            <div class="mb-4 border-b pb-2 flex justify-between items-center">
                <h2 class="text-lg font-semibold">Approval Response Form</h2>
                <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="{{ $approveMethod }}({{ $model->id }},'{{ $type }}')"
            x-on:submit.window="open = false">
                <div class="space-y-4">
                    {{-- Comments --}}
                    {{-- @if (!$isApprovedCheck) --}}
                    <div>
                        {{-- <label class="block text-sm font-medium mb-1">{{ $role }} comments</label> --}}
                        <label class="block text-sm font-medium mb-1">Comments</label>
                        <textarea wire:model="comment"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2" rows="4"
                            placeholder="Write your response..."></textarea>
                        @error('comment')
                        <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- @endif --}}

                    {{-- Decision --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Decision</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2" wire:model="status">
                            <option value="" selected>select</option>
                            @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                            @continue(in_array($row->value, ["Progress", "Pending", "Expired"]))
                            <option value="{{ $row }}">{{ $row }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-100">
                        Cancel
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="{{ $approveMethod }}"
                        class="px-4 py-2 rounded-md bg-[#134169] text-white hover:bg-red-500">
                        <span wire:loading.remove wire:target="{{ $approveMethod }}">
                            Submit
                        </span>
                        <span wire:loading wire:target="{{ $approveMethod }}">
                            <i class="bx bx-loader-alt fa-spin"></i> Processing...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
@endif