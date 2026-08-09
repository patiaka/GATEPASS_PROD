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
                <thead class="bg-[#134169] text-white">
                    <tr class="uppercase text-xs tracking-wider">
                        <th class="px-4 py-3 text-left font-medium">Reference</th>
                        <th class="px-4 py-3 text-left font-medium">Date</th>
                        <th class="px-4 py-3 text-left font-medium">Agent</th>
                        <th class="px-4 py-3 text-left font-medium">Company</th>
                        <th class="px-4 py-3 text-left font-medium">Vehicle</th>
                        <th class="px-4 py-3 text-left font-medium">driver</th>
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
                            <td class="px-4 py-4">#{{ $row->requestable->reference }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $row->created_at->format('d-m-Y H:i') }}</td>
                            <td class="px-4 py-4">{{ $row->user->name }}</td>
                            <td class="px-4 py-4">{{ $row->requestable->company }}</td>
                            <td class="px-4 py-4">#{{ $row->requestable->car_number }}</td>
                            <td class="px-4 py-4">{{ $row->car_driver ? $row->car_driver->name : 'N/A' }}</td>
                            <td class="px-4 py-4">{{ $row->car_driver ? $row->car_driver->department->name : 'N/A' }}
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
                                {{-- Lien classique (pas wire:navigate) : le select2 a besoin d'un
                                     chargement complet pour afficher la présélection --}}
                                <a href="{{ route('car.check_create', ['request' => $row->requestable_id]) }}"
                                    class="inline-flex items-center gap-1 text-[11px] px-2.5 py-1 rounded-md
                                           bg-[#134169] text-white border border-[#134169]
                                           hover:bg-white hover:text-[#134169] transition">
                                    Record {{ $row->action === 'Exit' ? 'Entry' : 'Exit' }}
                                </a>
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
