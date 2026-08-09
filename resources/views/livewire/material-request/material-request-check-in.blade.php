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

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm mb-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">

            {{-- Department --}}
            <div>
                <x-select label="Department" name="department" wire:model.live="department" class="w-full">
                    <option value="">All Departments</option>
                    @foreach ($departments as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Action --}}
            <div>
                <x-select label="Action" name="action" wire:model.live="action" class="w-full">
                    <option value="">All</option>
                    <option value="Entry">Entry</option>
                    <option value="Exit">Exit</option>
                </x-select>
            </div>

            {{-- Gate --}}
            <div>
                <x-select label="Gate" name="gate" wire:model.live="gate" class="w-full">
                    <option value="">All</option>
                    <option value="Front">Front</option>
                    <option value="Back">Back</option>
                    <option value="Airport">Airport</option>
                </x-select>
            </div>

            {{-- Date start --}}
            <div>
                {{-- <label class="block text-xs font-medium text-slate-600 mb-1">Date start</label> --}}
                <x-input type="date" wire:model.live="debut" class="w-full h-[42px]" label="Date Start"/>
            </div>

            {{-- Date end --}}
            <div>
                {{-- <label class="block text-xs font-medium text-slate-600 mb-1">Date end</label> --}}
                <x-input type="date" wire:model.live="fin" class="w-full h-[42px]" label="Date End"/>
            </div>

            {{-- Reset --}}
            <div class="flex items-end">
                <button wire:click='ResetFilter'
                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md flex items-center justify-center gap-2 h-[42px]">
                    <i data-lucide="x"></i>
                    Reset
                </button>
            </div>

        </div>

        {{-- Export (ligne séparée propre) --}}
        @if ($debut || $action || $gate)
            <div class="flex justify-end mt-4">
                <button wire:click="export" wire:loading.attr="disabled" wire:target="export"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#134169] text-white text-sm font-medium hover:bg-[#0f3550] transition">

                    <span wire:loading.remove wire:target="export">Export</span>
                    <span wire:loading wire:target="export">Processing...</span>

                </button>
            </div>
        @endif

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
                                <span
                                    class="text-[11px] px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200">
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
                            <td colspan="12" class="px-4 py-8 text-center text-sm text-gray-400">
                                No result
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
