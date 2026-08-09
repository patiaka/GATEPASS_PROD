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

    {{-- Search --}}
    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[240px]">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3M11 19a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search — reference, vehicle, company, agent, driver, gate, action..."
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
            </div>

            @if ($this->search !== '')
                <button wire:click="ResetFilter"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-slate-600 text-sm hover:bg-slate-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    Clear
                </button>
            @endif

            <button wire:click="export" wire:loading.attr="disabled" wire:target="export"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#0e3a61] text-white text-sm font-medium hover:bg-[#0c3253] shadow-sm transition disabled:opacity-60">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                </svg>
                <span wire:loading.remove wire:target="export">Export</span>
                <span wire:loading wire:target="export">Exporting…</span>
            </button>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-100">
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
                            <td class="px-4 py-4 text-slate-600">{{ $row->created_at }}</td>
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
                            <td class="px-4 py-4">{{ $row->action }}</td>

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
                            <td colspan="9" class="px-4 py-8 text-center text-sm text-slate-500">No result</td>
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
