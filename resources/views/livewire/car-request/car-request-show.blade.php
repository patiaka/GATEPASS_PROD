<div>
    @php
        $sv = $carRequest->status->value;
        $sStyles = [
            'Pending' => ['bg-orange-50 text-orange-700 ring-orange-200', 'bg-orange-500'],
            'Progress' => ['bg-yellow-50 text-yellow-700 ring-yellow-200', 'bg-yellow-500'],
            'Approved' => ['bg-emerald-50 text-emerald-700 ring-emerald-200', 'bg-emerald-500'],
            'Rejected' => ['bg-rose-50 text-rose-700 ring-rose-200', 'bg-rose-500'],
            'Expired' => ['bg-slate-100 text-slate-600 ring-slate-200', 'bg-slate-400'],
        ];
        [$sBadge, $sDot] = $sStyles[$sv] ?? $sStyles['Pending'];
        $fmt = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d-m-Y H:i') : null;
        $fmtDate = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d-m-Y') : '—';
        $stateOf = function (string $actor) use ($carRequest) {
            $l = $carRequest->getStatusFor($actor)[0] ?? '';
            return str_contains($l, 'Approved') ? 'approved' : (str_contains($l, 'Rejected') ? 'rejected' : 'pending');
        };
        $steps = [
            ['role' => 'Requester', 'name' => $carRequest->user->name, 'date' => $fmt($carRequest->getRawOriginal('created_at')), 'comment' => null, 'state' => 'approved'],
            ['role' => 'HOD', 'name' => $carRequest->hodApproval?->name, 'date' => $fmt($carRequest->hod_approval_date), 'comment' => $carRequest->hod_comment, 'state' => $stateOf('hod')],
        ];
        if ($carRequest->isRequiredDirectorApproval()) {
            $steps[] = ['role' => 'Director', 'name' => $carRequest->directorApproval?->name, 'date' => $fmt($carRequest->director_approval_date), 'comment' => $carRequest->director_comment, 'state' => $stateOf('director')];
        }
        $steps[] = ['role' => 'General Manager', 'name' => $carRequest->gmApproval?->name, 'date' => $fmt($carRequest->gm_approval_date), 'comment' => $carRequest->gm_comment, 'state' => $stateOf('gm')];

        $drivers = $carRequest->car_drivers;
        $passengers = $carRequest->passengers;
        $hasVehicle = $carRequest->somisy_car !== 'no_vehicle';
    @endphp

    <main class="p-4 md:p-6 space-y-6 bg-gray-50">

        {{-- ============ Header ============ --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#134169]">Resident &amp; Vehicle Off Site</h1>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-sm font-semibold text-slate-700">#{{ $carRequest->reference }}</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold ring-1 {{ $sBadge }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $sDot }}"></span>
                        {{ $sv }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('car.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </a>

                <a href="{{ route('car.edit', ['CarRequest' => $carRequest]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-blue-200 bg-blue-50 text-sm font-medium text-blue-700 hover:bg-blue-100 transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687 1.687M7 17l4-1 9-9a2.121 2.121 0 00-3-3l-9 9-1 4z" />
                    </svg>
                    Edit
                </a>

                <button wire:click="download_pdf({{ $carRequest }})" wire:loading.attr="disabled" wire:target="download_pdf"
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
            </div>
        </div>

        {{-- ============ 2-column layout ============ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ---------- Left ---------- --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Request details --}}
                <section class="bg-white border border-gray-200 shadow-sm rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#134169]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z" />
                        </svg>
                        <h2 class="font-semibold text-sm text-[#134169]">Request details</h2>
                    </div>

                    {{-- General --}}
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                        <div>
                            <dt class="text-xs text-slate-500">Requested by</dt>
                            <dd class="text-slate-800 font-medium">{{ $carRequest->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Department</dt>
                            <dd class="text-slate-800">{{ $carRequest->user->department?->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Company</dt>
                            <dd class="text-slate-800">{{ $carRequest->company }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Somisy vehicle</dt>
                            <dd class="text-slate-800">{{ $carRequest->somisy_car }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Camp resident</dt>
                            <dd class="text-slate-800">{{ $carRequest->resident }}</dd>
                        </div>
                        @if ($hasVehicle)
                            <div>
                                <dt class="text-xs text-slate-500">Vehicle type</dt>
                                <dd class="text-slate-800">{{ $carRequest->car_type ?: '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-500">Vehicle number</dt>
                                <dd class="text-slate-800 font-medium">{{ $carRequest->car_number ?: '—' }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs text-slate-500">Created at</dt>
                            <dd class="text-slate-600">{{ $fmt($carRequest->getRawOriginal('created_at')) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Updated at</dt>
                            <dd class="text-slate-600">{{ $carRequest->updated_at?->format('d-m-Y H:i') ?? '—' }}</dd>
                        </div>
                    </dl>

                    {{-- Trip & schedule --}}
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-5 mb-3 pt-4 border-t">Trip &amp; schedule</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                        <div>
                            <dt class="text-xs text-slate-500">Destination</dt>
                            <dd class="text-slate-800 font-medium">{{ $carRequest->destination }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Reason for travel</dt>
                            <dd class="text-slate-800">{{ $carRequest->reason }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Valid from</dt>
                            <dd class="text-slate-800">{{ $fmtDate($carRequest->start) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Valid until</dt>
                            <dd class="text-slate-800">{{ $fmtDate($carRequest->end) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Departure time</dt>
                            <dd class="text-slate-800">{{ $carRequest->depart_at }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Arrival time</dt>
                            <dd class="text-slate-800">{{ $carRequest->arrive_at }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs text-slate-500">Justification</dt>
                            <dd class="text-slate-800">{{ $carRequest->comment ?: '—' }}</dd>
                        </div>
                    </dl>
                </section>

                {{-- Drivers --}}
                @if ($drivers->isNotEmpty())
                    <section class="bg-white border border-gray-200 shadow-sm rounded-xl p-5">
                        <div class="flex items-center gap-2 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#134169]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-6" />
                            </svg>
                            <h2 class="font-semibold text-sm text-[#134169]">Drivers</h2>
                            <span class="text-xs text-slate-400">({{ $drivers->count() }})</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($drivers as $row)
                                <div class="flex items-center gap-3 rounded-lg border border-gray-100 bg-slate-50/60 px-3 py-2">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#134169]/10 text-[#134169] text-xs font-bold">
                                        {{ \Illuminate\Support\Str::of($row->user?->name)->substr(0, 1)->upper() }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-800 truncate">{{ $row->user?->name ?? '—' }}</p>
                                        <p class="text-xs text-slate-500">{{ $row->user?->contact ?? '—' }} · {{ $row->user?->badge_number ?? '—' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Passengers / residents --}}
                @if ($passengers->isNotEmpty())
                    <section class="bg-white border border-gray-200 shadow-sm rounded-xl p-5">
                        <div class="flex items-center gap-2 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#134169]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            <h2 class="font-semibold text-sm text-[#134169]">Residents / passengers</h2>
                            <span class="text-xs text-slate-400">({{ $passengers->count() }})</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($passengers as $row)
                                <div class="flex items-center gap-3 rounded-lg border border-gray-100 bg-slate-50/60 px-3 py-2">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#134169]/10 text-[#134169] text-xs font-bold">
                                        {{ \Illuminate\Support\Str::of($row->user?->name)->substr(0, 1)->upper() }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-800 truncate">{{ $row->user?->name ?? '—' }}</p>
                                        <p class="text-xs text-slate-500">{{ $row->user?->contact ?? '—' }} · {{ $row->user?->badge_number ?? '—' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            {{-- ---------- Right ---------- --}}
            <div class="space-y-6">

                {{-- Status + action --}}
                <section class="bg-white border border-gray-200 shadow-sm rounded-xl p-5">
                    <p class="text-xs text-slate-500 mb-1">Current status</p>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full {{ $sDot }}"></span>
                        <span class="text-lg font-bold text-slate-800">{{ $sv }}</span>
                    </div>
                    <div class="mt-3 pt-3 border-t text-xs text-slate-500 space-y-1">
                        <div class="flex justify-between"><span>Valid from</span><span class="text-slate-700 font-medium">{{ $fmtDate($carRequest->start) }}</span></div>
                        <div class="flex justify-between"><span>Valid until</span><span class="text-slate-700 font-medium">{{ $fmtDate($carRequest->end) }}</span></div>
                    </div>

                    @if (Auth::user()->canApprove($carRequest) && Auth::user()->isApprover())
                        <div class="mt-4">
                            <x-form-request :model="$carRequest" type="vehicle" />
                        </div>
                    @endif
                </section>

                {{-- Approval workflow --}}
                <section class="bg-white border border-gray-200 shadow-sm rounded-xl p-5">
                    <h2 class="font-semibold text-sm text-[#134169] mb-4">Approval workflow</h2>
                    <ol class="relative border-l border-gray-200 ml-2 space-y-5">
                        @foreach ($steps as $step)
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
                                    <p class="text-xs text-slate-600 mt-0.5">{{ $step['name'] }}</p>
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
    </main>
</div>
