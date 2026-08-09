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
                    placeholder="Search — reference, company, agent, delegated person, gate, action..."
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

        {{-- Period filter --}}
        <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap items-center gap-2">
            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 mr-1">Period</span>
            @foreach (['all' => 'All', 'today' => 'Today', '24h' => 'Last 24h', 'week' => 'This week', 'month' => 'This month'] as $key => $label)
                <button type="button" wire:click="setPeriod('{{ $key }}')" @class([
                    'px-3 py-1.5 rounded-full text-xs font-medium border transition',
                    'bg-[#134169] text-white border-[#134169] shadow-sm' => $period === $key,
                    'bg-white text-slate-600 border-gray-300 hover:bg-slate-50' => $period !== $key,
                ])>{{ $label }}</button>
            @endforeach
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200">

        <!-- Scroll wrapper -->
        <div class="overflow-auto max-h-[500px]">

            <table class="min-w-full text-[13px] table-fixed">

                <!-- THEAD -->
                <thead class="bg-slate-100 sticky top-0 z-10 text-xs tracking-wide text-slate-700">
                    <tr class="border-b">
                        <th class="px-4 py-2 w-24 text-left">ID</th>
                        <th class="px-4 py-2 w-40 text-left">Date</th>
                        <th class="px-4 py-2 w-40 text-left">Department</th>
                        <th class="px-4 py-2 w-40 text-left">Requestor</th>
                        <th class="px-4 py-2 w-40 text-left">Company</th>
                        <th class="px-4 py-2 w-40 text-left">Delegated Person Name</th>
                        <th class="px-4 py-2 w-40 text-left">Gate</th>
                        <th class="px-4 py-2 w-32 text-left">Action</th>
                        <th class="px-4 py-2 w-32 text-left">Record</th>

                    </tr>
                </thead>

                <!-- TBODY -->
                <tbody class="divide-y divide-gray-100">
                    @forelse ($this->rows as $row)
                        <tr wire:key="row-{{ $row->id }}"
                            class="odd:bg-white even:bg-gray-50 hover:bg-slate-100 transition">

                            <td class="px-4 py-2 font-medium text-gray-800">
                                #{{ $row->requestable->reference }}
                            </td>

                            <td class="px-4 py-2 text-gray-700">
                                {{ $row->created_at->format('Y-m-d H:i') }}
                            </td>

                            <td class="px-4 py-2 text-gray-700">
                                {{ $row->requestable->user->department->name }}
                            </td>

                            <td class="px-4 py-2 text-gray-700">
                                {{ $row->user->name }}
                            </td>

                            <td class="px-4 py-2 text-gray-700">
                                {{ $row->requestable->company }}
                            </td>

                            <td class="px-4 py-2 text-gray-700">
                                {{ $row->requestable->person_out?->name ?? $row->requestable->person_out_name ?? '—' }}
                            </td>

                            <td class="px-4 py-2 text-gray-700">
                                {{ $row->gate }}
                            </td>

                            <td class="px-4 py-2">
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

                            <td class="px-4 py-2">
                                {{-- Lien classique (pas wire:navigate) : le select2 a besoin d'un
                                     chargement complet pour afficher la présélection --}}
                                <a href="{{ route('material.check_create', ['request' => $row->requestable_id]) }}"
                                    class="inline-flex items-center gap-1 text-[11px] px-2.5 py-1 rounded-md
                                           bg-[#134169] text-white border border-[#134169]
                                           hover:bg-white hover:text-[#134169] transition">
                                    Record {{ $row->action === 'Exit' ? 'Entry' : 'Exit' }}
                                </a>
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
