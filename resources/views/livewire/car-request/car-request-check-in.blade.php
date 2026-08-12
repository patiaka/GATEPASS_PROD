<div>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b pb-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-[#134169]">Vehicle Check In / Out</h1>
            <p class="text-sm text-slate-500 mt-1">Vehicle Entrance and exit management</p>
        </div>

        @if (Auth::user()->isAdmin() || Auth::user()->isSecurity())
            <div class="flex items-center gap-3">
                <a href="{{ route('car.check_create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border bg-[#0e3a61] text-white text-sm font-medium
              hover:bg-[#0c3253] shadow-sm transition
              focus:outline-none focus:ring-2 focus:ring-white/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="text-sm font-medium">New Check In</span>
                </a>
            </div>
        @endif
    </div>

    {{-- Search + filters + export (barre unifiée) --}}
    @include('partials.checkin-toolbar', ['placeholder' => 'Search — reference, vehicle, company, agent, driver, gate, action...'])

    {{-- Table Card --}}
    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="uppercase text-xs tracking-wider text-slate-500">
                        <th class="px-4 py-3 text-left font-medium">Reference</th>
                        <th class="px-4 py-3 text-left font-medium">Date</th>
                        <th class="px-4 py-3 text-left font-medium">Agent</th>
                        <th class="px-4 py-3 text-left font-medium">Company</th>
                        <th class="px-4 py-3 text-left font-medium">Vehicle</th>
                        <th class="px-4 py-3 text-left font-medium">Driver / Resident</th>
                        <th class="px-4 py-3 text-left font-medium">department</th>
                        <th class="px-4 py-3 text-left font-medium">gate</th>
                        <th class="px-4 py-3 text-left font-medium">fuel level</th>
                        {{-- <th class="px-4 py-3 text-left font-medium">destination</th> --}}
                        <th class="px-4 py-3 text-left font-medium">kilometers / Per Hours</th>
                        <th class="px-4 py-3 text-left font-medium">Action</th>
                        <th class="px-4 py-3 text-left font-medium">Record</th>

                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse ($this->rows as $row)
                        <tr wire:key="row-{{ $row->id }}" class="hover:bg-slate-50">
                            <td class="px-4 py-4">
                                <a href="{{ route('car.show', ['CarRequest' => $row->requestable_id]) }}" wire:navigate
                                    class="font-medium text-[#134169] hover:underline">{{ $row->requestable->reference }}</a>
                            </td>
                            <td class="px-4 py-4 text-slate-600">{{ $row->created_at->format('d-m-Y H:i') }}</td>
                            <td class="px-4 py-4">{{ $row->user->name }}</td>
                            <td class="px-4 py-4">{{ $row->requestable->company }}</td>
                            <td class="px-4 py-4">
                                @if ($row->requestable->car_number)
                                    {{ $row->requestable->car_number }}
                                @else
                                    <span class="inline-flex items-center gap-1 rounded bg-amber-50 px-1.5 py-0.5 text-[10px] font-medium text-amber-700 ring-1 ring-inset ring-amber-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ __('Resident') }}
                                    </span>
                                @endif
                            </td>
                            @php
                                $resident = $row->requestable->passengers->map(fn ($p) => $p->user?->name)->filter()->join(', ');
                                $residentDept = $row->requestable->passengers->first()?->user?->department?->name;
                            @endphp
                            <td class="px-4 py-4">
                                @if ($row->car_driver)
                                    {{ $row->car_driver->name }}
                                @elseif ($resident !== '')
                                    {{ $resident }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if ($row->car_driver)
                                    {{ $row->car_driver->department?->name ?? 'N/A' }}
                                @elseif ($residentDept)
                                    {{ $residentDept }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-4">{{ $row->gate }}</td>
                            <td class="px-4 py-4">{{ $row->fuel_level }}</td>
                            {{-- <td class="px-4 py-4">{{ $row->destination }}</td> --}}
                            <td class="px-4 py-4">{{ $row->kilometers }}</td>
                            <td class="px-4 py-4">
                                <span @class([
                                    'inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium ring-1',
                                    'bg-amber-50 text-amber-700 ring-amber-200' => $row->action === 'Exit',
                                    'bg-emerald-50 text-emerald-700 ring-emerald-200' => $row->action === 'Entry',
                                ])>
                                    <span @class([
                                        'w-1.5 h-1.5 rounded-full',
                                        'bg-amber-500' => $row->action === 'Exit',
                                        'bg-emerald-500' => $row->action === 'Entry',
                                    ])></span>
                                    {{ $row->action }}
                                </span>
                            </td>

                            <td class="px-4 py-4">
                                @if (Auth::user()->isAdmin() || Auth::user()->isSecurity())
                                    {{-- Lien classique (pas wire:navigate) : le select2 a besoin d'un
                                         chargement complet pour afficher la présélection --}}
                                    <a href="{{ route('car.check_create', ['request' => $row->requestable_id]) }}"
                                        class="inline-flex items-center gap-1 text-[11px] px-2.5 py-1 rounded-md
                                               bg-[#134169] text-white border border-[#134169]
                                               hover:bg-white hover:text-[#134169] transition">
                                        Record {{ $row->action === 'Exit' ? 'Entry' : 'Exit' }}
                                    </a>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-4 py-10 text-center">
                                <div class="flex flex-col items-center gap-2 text-slate-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" />
                                    </svg>
                                    <span class="text-sm">No record for this filter.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($this->rows)
            <div class="p-4">
                {{ $this->rows->links() }}
            </div>
        @endif
    </div>
</div>
