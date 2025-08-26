@props(['type','model'])

@php
$isHod = Auth::user()->isHod();
$isGm = Auth::user()->isGm();

$role = $isHod ? 'HOD' : ($isGm ? 'GM' : '');
$approveMethod = $isHod ? 'approveByHod' : 'approveByGm';
$commentField = $isHod ? 'hod_comment' : 'gm_comment';
$isApprovedCheck = $isHod ? $model->isHodApproved() : $model->isGmApproved();
@endphp

@if (!$isApprovedCheck && ($isHod || $isGm))
<button x-data @click="$dispatch('open-modal', { id: 'dialog-{{ $model->id }}' })"
    class="text-blue-600 border border-blue-600 px-3 py-1 rounded hover:bg-blue-600 hover:text-white">
    Submit Response
</button>
@endif

<!-- Modal -->
<div x-data="{ open: false }" x-on:open-modal.window="if($event.detail.id === 'dialog-{{ $model->id }}') open = true"
    x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50" x-cloak>
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative" @click.away="open = false">
        <!-- Header -->
        <div class="mb-4 border-b pb-2 flex justify-between items-center">
            <h2 class="text-lg font-semibold">Approval Response Form</h2>
            <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>

        <!-- Form -->
        <form wire:submit="{{ $approveMethod }}({{ $model->id }},'{{ $type }}')">
            <div class="space-y-4">
                {{-- Comments --}}
                @if (!$isApprovedCheck)
                <div>
                    <label class="block text-sm font-medium mb-1">{{ $role }} comments</label>
                    <textarea wire:model="{{ $commentField }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2" rows="4"
                        placeholder="Write your response..."></textarea>
                    @error($commentField)
                    <small class="text-red-600">{{ $message }}</small>
                    @enderror
                </div>
                @endif

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
                        Approve as {{ $role }}
                    </span>
                    <span wire:loading wire:target="{{ $approveMethod }}">
                        <i class="bx bx-loader-alt fa-spin"></i> Processing...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
