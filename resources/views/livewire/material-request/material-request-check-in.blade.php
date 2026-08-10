<div>
    <div class="flex justify-between items-center border-b pb-4 mb-5">
        <!-- Header -->
        <div>
            <!-- Title -->
            <h1 class="text-2xl font-bold text-[#134169] tracking-tight">
                Material Check In / Out
            </h1>
            <p class="text-sm text-slate-500 mt-1">Site Materials Entrance and exit management</p>
        </div>

        <!-- Button -->
        @if (Auth::user()->isAdmin() || Auth::user()->isSecurity())
            <a href="{{ route('material.check_create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
              bg-[#134169] text-white text-sm font-semibold
              hover:bg-[#0e3a61] shadow-md transition
              focus:outline-none focus:ring-2 focus:ring-[#134169]/30">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>

                New Check In
            </a>
        @endif

    </div>

    {{-- Search + filters + export (barre unifiée) --}}
    @include('partials.checkin-toolbar', ['placeholder' => 'Search — reference, company, agent, delegated person, gate, action...'])

    {{-- Table Card --}}
    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="uppercase text-xs tracking-wider text-slate-500">
                        <th class="px-4 py-3 text-left font-medium">Reference</th>
                        <th class="px-4 py-3 text-left font-medium">Date</th>
                        <th class="px-4 py-3 text-left font-medium">Department</th>
                        <th class="px-4 py-3 text-left font-medium">Requestor</th>
                        <th class="px-4 py-3 text-left font-medium">Company</th>
                        <th class="px-4 py-3 text-left font-medium">Delegated Person</th>
                        <th class="px-4 py-3 text-left font-medium">Gate</th>
                        <th class="px-4 py-3 text-left font-medium">Action</th>
                        <th class="px-4 py-3 text-left font-medium">Record</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse ($this->rows as $row)
                        <tr wire:key="row-{{ $row->id }}" class="hover:bg-slate-50">

                            <td class="px-4 py-4">#{{ $row->requestable->reference }}</td>

                            <td class="px-4 py-4 text-slate-600">
                                {{ $row->created_at->format('d-m-Y H:i') }}
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                {{ $row->requestable->user->department->name }}
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                {{ $row->user->name }}
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                {{ $row->requestable->company }}
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                {{ $row->requestable->person_out?->name ?? $row->requestable->person_out_name ?? '—' }}
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                {{ $row->gate }}
                            </td>

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
                                    <a href="{{ route('material.check_create', ['request' => $row->requestable_id]) }}"
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
                            <td colspan="9" class="px-4 py-10 text-center">
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
