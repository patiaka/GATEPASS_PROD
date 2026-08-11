<div>
    @php
        $statusValue = $MaterialRequest->status->value;
        $statusStyles = [
            'Pending' => ['bg-orange-50 text-orange-700 ring-orange-200', 'bg-orange-500'],
            'Progress' => ['bg-yellow-50 text-yellow-700 ring-yellow-200', 'bg-yellow-500'],
            'Approved' => ['bg-emerald-50 text-emerald-700 ring-emerald-200', 'bg-emerald-500'],
            'Rejected' => ['bg-rose-50 text-rose-700 ring-rose-200', 'bg-rose-500'],
            'Expired' => ['bg-slate-100 text-slate-600 ring-slate-200', 'bg-slate-400'],
            'Cancelled' => ['bg-gray-200 text-gray-600 ring-gray-300', 'bg-gray-500'],
        ];
        [$statusBadge, $statusDot] = $statusStyles[$statusValue] ?? $statusStyles['Pending'];

        // Circuit d'approbation
        $fmt = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d-m-Y H:i') : null;
        $stateOf = function (string $actor) use ($MaterialRequest) {
            $label = $MaterialRequest->getStatusFor($actor)[0] ?? '';
            return str_contains($label, 'Approved') ? 'approved' : (str_contains($label, 'Rejected') ? 'rejected' : 'pending');
        };

        $steps = [];
        $steps[] = [
            'role' => 'Requester', 'name' => $MaterialRequest->user->name,
            'dept' => $MaterialRequest->user->department?->name, 'poste' => $MaterialRequest->user->poste,
            'date' => $fmt($MaterialRequest->getRawOriginal('created_at')), 'comment' => null, 'state' => 'approved',
        ];
        $steps[] = [
            'role' => 'HOD', 'name' => $MaterialRequest->hodApproval?->name,
            'dept' => $MaterialRequest->hodApproval?->department?->name, 'poste' => $MaterialRequest->hodApproval?->poste,
            'date' => $fmt($MaterialRequest->hod_approval_date), 'comment' => $MaterialRequest->hod_comment, 'state' => $stateOf('hod'),
        ];
        if ($MaterialRequest->isRequiredDirectorApproval()) {
            $steps[] = [
                'role' => 'Director', 'name' => $MaterialRequest->directorApproval?->name,
                'dept' => $MaterialRequest->directorApproval?->department?->name, 'poste' => $MaterialRequest->directorApproval?->poste,
                'date' => $fmt($MaterialRequest->director_approval_date), 'comment' => $MaterialRequest->director_comment, 'state' => $stateOf('director'),
            ];
        }
        $steps[] = [
            'role' => 'General Manager', 'name' => $MaterialRequest->gmApproval?->name,
            'dept' => $MaterialRequest->gmApproval?->department?->name, 'poste' => $MaterialRequest->gmApproval?->poste,
            'date' => $fmt($MaterialRequest->gm_approval_date), 'comment' => $MaterialRequest->gm_comment, 'state' => $stateOf('gm'),
        ];

        $items = $MaterialRequest->loadMissing('material_request_items')->material_request_items;
        $documents = $MaterialRequest->loadMissing('documents')->documents;
        $totalQty = $items->sum('quantity');
    @endphp

    <main class="p-4 md:p-6 space-y-6 bg-gray-50">

        {{-- ============ Header ============ --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#134169]">Material Request Details</h1>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-sm font-semibold text-slate-700">#{{ $MaterialRequest->reference }}</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold ring-1 {{ $statusBadge }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                        {{ $statusValue }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('material.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </a>

                @can('update-request', $MaterialRequest)
                    <a href="{{ route('material.edit', ['MaterialRequest' => $MaterialRequest]) }}"
                        @class([
                            'inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm border',
                            'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100' => $MaterialRequest->isRejected(),
                            'border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100' => ! $MaterialRequest->isRejected(),
                        ])>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687 1.687M7 17l4-1 9-9a2.121 2.121 0 00-3-3l-9 9-1 4z" />
                        </svg>
                        {{ $MaterialRequest->isRejected() ? 'Revise & resubmit' : 'Edit' }}
                    </a>
                @endcan

                <button wire:click="duplicate" wire:loading.attr="disabled" wire:target="duplicate"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition shadow-sm disabled:opacity-60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8a2 2 0 012 2v8a2 2 0 01-2 2H8a2 2 0 01-2-2V9a2 2 0 012-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2h2" />
                    </svg>
                    <span wire:loading.remove wire:target="duplicate">Duplicate</span>
                    <span wire:loading wire:target="duplicate">Duplicating…</span>
                </button>

                @can('download-request', $MaterialRequest)
                <button wire:click="download_pdf({{ $MaterialRequest->id }})" wire:loading.attr="disabled" wire:target="download_pdf"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#0e3a61] text-white text-sm font-medium hover:bg-[#0c3252] disabled:opacity-60 disabled:cursor-not-allowed transition shadow-sm">
                    <span wire:loading.remove wire:target="download_pdf" class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 4v12" />
                        </svg>
                        Download PDF
                    </span>
                    <span wire:loading wire:target="download_pdf" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25" stroke-width="4" />
                            <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                        </svg>
                        Generating…
                    </span>
                </button>
                @endcan

                @can('cancel-request', $MaterialRequest)
                    <button wire:click="cancel" wire:target="cancel" wire:loading.attr="disabled"
                        wire:confirm="Cancel this request? It will no longer be valid."
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-rose-200 bg-rose-50 text-sm font-medium text-rose-700 hover:bg-rose-100 disabled:opacity-60 transition shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6M5 7h14M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m1 0v12a2 2 0 01-2 2H8a2 2 0 01-2-2V7" />
                        </svg>
                        <span wire:loading.remove wire:target="cancel">Cancel request</span>
                        <span wire:loading wire:target="cancel">Cancelling…</span>
                    </button>
                @endcan
            </div>
        </div>

        {{-- ============ KPI tiles ============ --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <p class="text-xs text-slate-500">Items</p>
                <p class="text-xl font-bold text-[#134169]">{{ $items->count() }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <p class="text-xs text-slate-500">Total quantity</p>
                <p class="text-xl font-bold text-[#134169]">{{ $totalQty }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <p class="text-xs text-slate-500">Documents</p>
                <p class="text-xl font-bold text-[#134169]">{{ $documents->count() }}</p>
            </div>
        </div>

        {{-- ============ 2-column layout ============ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ---------- Left : items + documents ---------- --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Requested Items --}}
                <section class="bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden">
                    <div class="flex items-center gap-2 px-5 py-3 border-b bg-slate-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#134169]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7 12 3 4 7m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <h2 class="font-semibold text-sm text-[#134169]">Requested Items</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-wider">
                                <tr class="border-b border-gray-100">
                                    <th class="px-4 py-2.5 text-center font-semibold w-12">#</th>
                                    <th class="px-4 py-2.5 text-left font-semibold">Description</th>
                                    <th class="px-4 py-2.5 text-center font-semibold w-24">Quantity</th>
                                    <th class="px-4 py-2.5 text-left font-semibold">Additional info</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($items as $row)
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="px-4 py-2.5 text-center text-slate-400">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2.5 text-slate-800 font-medium">{{ $row->designation }}</td>
                                        <td class="px-4 py-2.5 text-center text-slate-700">{{ $row->quantity }}</td>
                                        <td class="px-4 py-2.5 text-slate-500">{{ $row->serial_number ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t border-gray-200 bg-slate-50/60">
                                    <td colspan="2" class="px-4 py-2.5 text-right text-xs font-semibold text-slate-500">Total</td>
                                    <td class="px-4 py-2.5 text-center font-bold text-[#134169]">{{ $totalQty }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>

                {{-- Attached Documents --}}
                <section class="bg-white border border-gray-200 shadow-sm rounded-xl p-5" x-data="{ lightbox: null }">
                    <div class="flex items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#134169]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v9a2.25 2.25 0 0 0 4.5 0V6.75" />
                        </svg>
                        <h2 class="font-semibold text-sm text-[#134169]">Attached Documents</h2>
                        <span class="text-xs text-slate-400">({{ $documents->count() }})</span>
                    </div>

                    @if ($documents->isEmpty())
                        <p class="text-sm text-slate-400">No document attached.</p>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($documents as $row)
                                <div class="relative group bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition flex items-center justify-center">
                                    <img src="{{ $row->DocLink() }}" alt="Document image"
                                        @click="lightbox = '{{ $row->DocLink() }}'"
                                        class="w-full max-h-56 object-contain bg-white p-2 cursor-zoom-in hover:opacity-90 transition" />
                                    @if ($MaterialRequest->user_id === Auth::user()->id && $MaterialRequest->isPending())
                                        <div class="absolute top-2 right-2 flex gap-2 bg-white/80 p-1 rounded shadow-sm">
                                            <x-button-edit href="{{ route('document.edit', ['document' => $row]) }}" />
                                            <x-button-delete url="{{ url('document/' . $row->id) }}" />
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Lightbox --}}
                        <template x-teleport="body">
                            <div x-show="lightbox" x-cloak x-transition.opacity
                                @click="lightbox = null" @keydown.escape.window="lightbox = null"
                                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 p-4 cursor-zoom-out">
                                <img :src="lightbox" alt="Document" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" @click.stop>
                                <button type="button" @click="lightbox = null"
                                    class="absolute top-4 right-4 inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 text-white hover:bg-white/20 transition" aria-label="Close">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    @endif
                </section>
            </div>

            {{-- ---------- Right : status + meta + timeline ---------- --}}
            <div class="space-y-6">

                {{-- Status card + action --}}
                <section class="bg-white border border-gray-200 shadow-sm rounded-xl p-5">
                    <p class="text-xs text-slate-500 mb-1">Current status</p>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full {{ $statusDot }}"></span>
                        <span class="text-lg font-bold text-slate-800">{{ $statusValue }}</span>
                    </div>

                    @if ($MaterialRequest->expire_at)
                        <div class="mt-3 pt-3 border-t text-xs text-slate-500">
                            <span class="font-medium text-slate-600">Expires:</span>
                            {{ \Illuminate\Support\Carbon::parse($MaterialRequest->expire_at)->format('d-m-Y') }}
                        </div>
                    @endif

                    @if (Auth::user()->canApprove($MaterialRequest) && Auth::user()->isApprover())
                        <div class="mt-4">
                            <x-form-request :model="$MaterialRequest" type="material" />
                        </div>
                    @endif
                </section>

                {{-- Requester --}}
                <section class="bg-white border border-gray-200 shadow-sm rounded-xl p-5">
                    <h2 class="font-semibold text-sm text-[#134169] mb-3">Requester</h2>
                    <dl class="space-y-2.5 text-sm">
                        <div class="flex justify-between gap-2">
                            <dt class="text-slate-500">Name</dt>
                            <dd class="text-slate-800 font-medium text-right">{{ $MaterialRequest->user->name }}</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt class="text-slate-500">Department</dt>
                            <dd class="text-slate-800 text-right">{{ $MaterialRequest->user->department?->name ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt class="text-slate-500">Position</dt>
                            <dd class="text-slate-800 text-right">{{ $MaterialRequest->user->poste }}</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt class="text-slate-500">Delegated person</dt>
                            <dd class="text-slate-800 text-right">{{ $MaterialRequest->person_out?->name ?? $MaterialRequest->person_out_name ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt class="text-slate-500">Company</dt>
                            <dd class="text-slate-800 text-right">{{ $MaterialRequest->company }}</dd>
                        </div>
                        <div class="flex justify-between gap-2 pt-2 border-t">
                            <dt class="text-slate-500">Created</dt>
                            <dd class="text-slate-600 text-right">{{ $fmt($MaterialRequest->getRawOriginal('created_at')) }}</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt class="text-slate-500">Updated</dt>
                            <dd class="text-slate-600 text-right">{{ $MaterialRequest->updated_at?->format('d-m-Y H:i') ?? '—' }}</dd>
                        </div>
                    </dl>
                </section>

                {{-- Approval timeline --}}
                <section class="bg-white border border-gray-200 shadow-sm rounded-xl p-5">
                    <h2 class="font-semibold text-sm text-[#134169] mb-4">Approval workflow</h2>
                    <ol class="relative border-l border-gray-200 ml-2 space-y-5">
                        @foreach ($steps as $step)
                            @php
                                $dot = match ($step['state']) {
                                    'approved' => 'bg-emerald-500',
                                    'rejected' => 'bg-rose-500',
                                    default => 'bg-slate-300',
                                };
                                $badge = match ($step['state']) {
                                    'approved' => ['Approved', 'text-emerald-700 bg-emerald-50 ring-emerald-200'],
                                    'rejected' => ['Rejected', 'text-rose-700 bg-rose-50 ring-rose-200'],
                                    default => ['Pending', 'text-slate-500 bg-slate-50 ring-slate-200'],
                                };
                            @endphp
                            <li class="ml-5">
                                <span class="absolute -left-[7px] flex items-center justify-center w-3.5 h-3.5 rounded-full ring-4 ring-white {{ $dot }}"></span>
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-sm font-semibold text-slate-700">{{ $step['role'] }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold ring-1 {{ $badge[1] }}">{{ $badge[0] }}</span>
                                </div>
                                @if ($step['name'])
                                    <p class="text-xs text-slate-600 mt-0.5">{{ $step['name'] }}@if ($step['poste']) · {{ $step['poste'] }}@endif</p>
                                @endif
                                @if ($step['date'])
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $step['date'] }}</p>
                                @endif
                                @if ($step['comment'])
                                    <p class="text-xs text-slate-500 mt-1 italic bg-slate-50 rounded-md px-2 py-1">“{{ $step['comment'] }}”</p>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </section>
            </div>
        </div>

        {{-- ============ Notes ============ --}}
        <section class="flex gap-3 rounded-xl border border-blue-100 bg-blue-50/50 p-4">
            <div class="shrink-0 mt-0.5">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-[#134169]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25 12 12v4.5m0-9h.008M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </span>
            </div>
            <div>
                <p class="text-sm font-semibold text-[#134169] mb-1">Good to know</p>
                <ul class="space-y-1 text-xs text-slate-600">
                    <li class="flex gap-2">
                        <span class="text-blue-400">•</span>
                        Items may be removed from site on specified dates. Up to seven days can be nominated for multiple entries/exits.
                    </li>
                    <li class="flex gap-2">
                        <span class="text-blue-400">•</span>
                        Final approval must come from the designated General Manager depending on the department.
                    </li>
                </ul>
            </div>
        </section>

    </main>
</div>
