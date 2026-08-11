<div>
    <div class="card bg-white shadow-sm p-3 sm:p-5">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-4 sm:p-6 lg:p-8">

                {{-- Header --}}
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-[#0f4b73] tracking-tight">
                        Material Request Check-In / Check-Out
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Material gate movement record
                    </p>
                </div>

                <form wire:submit.prevent="recordSecurityCheck" class="space-y-6">

                    {{-- Material Request --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Material Request
                        </label>

                        <x-select2 :options="$materialRequests" optionLabel="full_name" wire:model="material_request_id"
                            name="material_request_id" placeholder="Select material request" />

                        @error('material_request_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror

                        @if ($material_request_id)
                            @if ($last_movement)
                                <p class="text-xs text-slate-500 mt-2">
                                    Last movement: <span class="font-semibold text-[#134169]">{{ $last_movement }}</span>
                                    — suggested action preselected.
                                </p>
                            @else
                                <p class="text-xs text-slate-500 mt-2">
                                    No previous movement — <span class="font-semibold text-[#134169]">Exit</span> preselected.
                                </p>
                            @endif
                        @endif
                    </div>

                    {{-- Gate (RADIO CARDS — SAME STYLE) --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">
                            Gate <span class="text-red-500">*</span>
                        </label>

                        <div class="grid grid-cols-3 gap-3">
                            @foreach (['Front', 'Back', 'Airport'] as $g)
                                <label
                                    class="flex items-start gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50
                              hover:bg-blue-50 hover:border-[#134169] cursor-pointer transition">
                                    <input type="radio" wire:model="gate" value="{{ $g }}"
                                        class="mt-1 text-[#134169] focus:ring-[#134169]">

                                    <div>
                                        <div class="font-semibold text-slate-800">{{ $g }}</div>
                                        <div class="text-xs text-slate-500">Security gate</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        @error('gate')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Action --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Action <span class="text-red-500">*</span>
                        </label>

                        <select wire:model="action"
                            class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2
                       focus:ring-2 focus:ring-[#134169] focus:border-transparent outline-none">
                            <option value="">Select action</option>
                            <option value="Exit">Exit</option>
                            <option value="Entry">Entry</option>
                        </select>

                        @error('action')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">

                        <x-form-action cancel="material.check" target="recordSecurityCheck" />

                    </div>

                </form>
            </div>




            {{-- RIGHT COLUMN: MATERIAL REQUEST DETAILS --}}
            <div class="relative">
                {{-- LOADER --}}
                <div wire:loading wire:target="material_request_id"
                    class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-20">

                    <div class="flex flex-col items-center gap-2">
                        <svg class="animate-spin h-10 w-10 text-[#134169]" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>

                        <p class="text-sm text-gray-600">Loading request details...</p>
                    </div>
                </div>
                @if ($materialRequest)
                    @php
                        $sv = $materialRequest->status->value;
                        $sStyles = [
                            'Pending' => ['bg-orange-50 text-orange-700 ring-orange-200', 'bg-orange-500'],
                            'Progress' => ['bg-yellow-50 text-yellow-700 ring-yellow-200', 'bg-yellow-500'],
                            'Approved' => ['bg-emerald-50 text-emerald-700 ring-emerald-200', 'bg-emerald-500'],
                            'Rejected' => ['bg-rose-50 text-rose-700 ring-rose-200', 'bg-rose-500'],
                            'Expired' => ['bg-slate-100 text-slate-600 ring-slate-200', 'bg-slate-400'],
                        ];
                        [$sBadge, $sDot] = $sStyles[$sv] ?? $sStyles['Pending'];
                        $ciFmt = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d-m-Y H:i') : null;
                        $ciState = function (string $actor) use ($materialRequest) {
                            $l = $materialRequest->getStatusFor($actor)[0] ?? '';
                            return str_contains($l, 'Approved') ? 'approved' : (str_contains($l, 'Rejected') ? 'rejected' : 'pending');
                        };
                        $ciSteps = [
                            ['role' => 'Requester', 'name' => $materialRequest->user->name, 'poste' => $materialRequest->user->poste, 'date' => $ciFmt($materialRequest->getRawOriginal('created_at')), 'state' => 'approved'],
                            ['role' => 'HOD', 'name' => $materialRequest->hodApproval?->name, 'poste' => $materialRequest->hodApproval?->poste, 'date' => $ciFmt($materialRequest->hod_approval_date), 'state' => $ciState('hod')],
                        ];
                        if ($materialRequest->isRequiredDirectorApproval()) {
                            $ciSteps[] = ['role' => 'Director', 'name' => $materialRequest->directorApproval?->name, 'poste' => $materialRequest->directorApproval?->poste, 'date' => $ciFmt($materialRequest->director_approval_date), 'state' => $ciState('director')];
                        }
                        $ciSteps[] = ['role' => 'General Manager', 'name' => $materialRequest->gmApproval?->name, 'poste' => $materialRequest->gmApproval?->poste, 'date' => $ciFmt($materialRequest->gm_approval_date), 'state' => $ciState('gm')];
                        $ciItems = $materialRequest->material_request_items;
                    @endphp

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6" wire:loading.remove
                        wire:target="material_request_id">

                        {{-- Header --}}
                        <div class="flex items-center justify-between gap-3 border-b pb-3">
                            <div>
                                <h2 class="font-semibold text-base text-[#134169]">Request details</h2>
                                <p class="text-xs text-slate-500 mt-0.5">#{{ $materialRequest->reference }}</p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold ring-1 {{ $sBadge }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $sDot }}"></span>
                                {{ $sv }}
                            </span>
                        </div>

                        {{-- Request info --}}
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3 text-sm">
                            <div>
                                <dt class="text-xs text-slate-500">Requester</dt>
                                <dd class="text-slate-800 font-medium">{{ $materialRequest->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500">Department</dt>
                                <dd class="text-slate-800">{{ $materialRequest->user->department?->name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500">Company</dt>
                                <dd class="text-slate-800">{{ $materialRequest->company ?: '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500">Delegated person</dt>
                                <dd class="text-slate-800">{{ $materialRequest->person_out?->name ?? $materialRequest->person_out_name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500">Created</dt>
                                <dd class="text-slate-600">{{ $ciFmt($materialRequest->getRawOriginal('created_at')) }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500">Updated</dt>
                                <dd class="text-slate-600">{{ $materialRequest->updated_at?->format('d-m-Y H:i') ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500">Expires</dt>
                                <dd @class([
                                    'font-medium',
                                    'text-rose-600' => $materialRequest->expire_at && \Illuminate\Support\Carbon::parse($materialRequest->expire_at)->isPast(),
                                    'text-slate-800' => ! ($materialRequest->expire_at && \Illuminate\Support\Carbon::parse($materialRequest->expire_at)->isPast()),
                                ])>
                                    {{ $materialRequest->expire_at ? \Illuminate\Support\Carbon::parse($materialRequest->expire_at)->format('d-m-Y') : '—' }}
                                </dd>
                            </div>
                        </dl>

                        {{-- Material items --}}
                        <div>
                            <h3 class="font-semibold text-sm text-[#134169] mb-2">Material items</h3>
                            <div class="rounded-xl border border-gray-200 overflow-hidden">
                                <table class="w-full text-sm">
                                    <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-wider">
                                        <tr class="border-b border-gray-100">
                                            <th class="px-3 py-2 text-center font-semibold w-10">#</th>
                                            <th class="px-3 py-2 text-left font-semibold">Designation</th>
                                            <th class="px-3 py-2 text-center font-semibold w-16">Qty</th>
                                            <th class="px-3 py-2 text-left font-semibold">Info</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($ciItems as $row)
                                            <tr class="hover:bg-slate-50/70 transition-colors">
                                                <td class="px-3 py-2 text-center text-slate-400">{{ $loop->iteration }}</td>
                                                <td class="px-3 py-2 text-slate-800 font-medium">{{ $row->designation }}</td>
                                                <td class="px-3 py-2 text-center text-slate-700">{{ $row->quantity }}</td>
                                                <td class="px-3 py-2 text-slate-500">{{ $row->serial_number ?: '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-t border-gray-200 bg-slate-50/60">
                                            <td colspan="2" class="px-3 py-2 text-right text-xs font-semibold text-slate-500">Total</td>
                                            <td class="px-3 py-2 text-center font-bold text-[#134169]">{{ $ciItems->sum('quantity') }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        {{-- Approval workflow --}}
                        <div>
                            <h3 class="font-semibold text-sm text-[#134169] mb-4">Approval workflow</h3>
                            <ol class="relative border-l border-gray-200 ml-2 space-y-4">
                                @foreach ($ciSteps as $step)
                                    @php
                                        $d = match ($step['state']) { 'approved' => 'bg-emerald-500', 'rejected' => 'bg-rose-500', default => 'bg-slate-300' };
                                        $b = match ($step['state']) {
                                            'approved' => ['Approved', 'text-emerald-700 bg-emerald-50 ring-emerald-200'],
                                            'rejected' => ['Rejected', 'text-rose-700 bg-rose-50 ring-rose-200'],
                                            default => ['Pending', 'text-slate-500 bg-slate-50 ring-slate-200'],
                                        };
                                    @endphp
                                    <li class="ml-5">
                                        <span class="absolute -left-[7px] w-3.5 h-3.5 rounded-full ring-4 ring-white {{ $d }}"></span>
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-sm font-semibold text-slate-700">{{ $step['role'] }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold ring-1 {{ $b[1] }}">{{ $b[0] }}</span>
                                        </div>
                                        @if ($step['name'])
                                            <p class="text-xs text-slate-600 mt-0.5">{{ $step['name'] }}@if ($step['poste']) · {{ $step['poste'] }}@endif</p>
                                        @endif
                                        @if ($step['date'])
                                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $step['date'] }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </div>

                        {{-- Attached Documents --}}
                        <div>
                            <h3 class="font-semibold text-sm text-[#134169] mb-3">Attached documents
                                <span class="text-xs font-normal text-slate-400">({{ $materialRequest->documents->count() }})</span>
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                                x-data="{ lightbox: null }">
                                @foreach ($materialRequest->loadMissing('documents')->documents as $row)
                                    <div
                                        class="relative bg-white rounded-xl border border-gray-200
            overflow-hidden shadow-sm hover:shadow-md
            transition-all duration-200 flex items-center justify-center">

                                        <img src="{{ $row->DocLink() }}" alt="Document image"
                                            @click="lightbox = '{{ $row->DocLink() }}'"
                                            class="w-full max-h-56 object-contain bg-white p-2 cursor-zoom-in hover:opacity-90 transition" />

                                    </div>
                                @endforeach

                                {{-- Lightbox (plein écran) --}}
                                <template x-teleport="body">
                                    <div x-show="lightbox" x-cloak x-transition.opacity
                                        @click="lightbox = null" @keydown.escape.window="lightbox = null"
                                        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 p-4 cursor-zoom-out">
                                        <img :src="lightbox" alt="Document"
                                            class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" @click.stop>
                                        <button type="button" @click="lightbox = null"
                                            class="absolute top-4 right-4 inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 text-white hover:bg-white/20 transition"
                                            aria-label="Close">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 italic mt-4">Select a material request to preview its details.</p>
                @endif
            </div>

        </div>
    </div>

</div>
