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
<button command="show-modal" commandfor="dialog-{{ $model->id }}"
    class="text-blue-600 border border-blue-600 px-3 py-1 rounded hover:bg-blue-600 hover:text-white">
    Submit Response
</button>
@endif

<!-- Modal -->
<el-dialog>
    <dialog id="dialog-{{ $model->id }}" aria-labelledby="dialog-title"
        class="fixed inset-0 overflow-y-auto bg-transparent backdrop:bg-transparent">

        <el-dialog-backdrop class="fixed inset-0 bg-gray-500/75 transition-opacity"></el-dialog-backdrop>

        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <el-dialog-panel
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                <div class="bg-white w-full p-6">
                    <div class="mb-4 border-b pb-2">
                        <h2 class="text-lg font-semibold text-center">Approval Response Form</h2>
                    </div>

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
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            @endif

                            {{-- Decision --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">Decision</label>
                                <select class="w-full border border-gray-300 rounded-lg px-3 py-2" wire:model="status">
                                    @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                                    @continue($row->value === "Progress" || $model->value === "Pending" || $row->value
                                    === "Expired")
                                    <option value="{{ $row }}">{{ $row }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" command="close" commandfor="dialog-{{ $model->id }}"
                                wire:loading.attr="disabled" wire:target="{{ $approveMethod }}"
                                class="inline-flex w-full justify-center rounded-md bg-[#134169] px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-500 sm:ml-3 sm:w-auto">
                                <span wire:loading.remove wire:target="{{ $approveMethod }}">
                                    Approve as {{ $role }}
                                </span>
                                <span wire:loading wire:target="{{ $approveMethod }}">
                                    <i class="bx bx-loader-alt fa-spin"></i> Processing...
                                </span>
                            </button>

                            <button type="button" command="close" commandfor="dialog-{{ $model->id }}"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>