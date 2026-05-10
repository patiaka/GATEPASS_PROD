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

    {{-- Filters --}}
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
                        <th class="px-4 py-3 text-left font-medium">Decision</th>

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
                                @if ($row->decision === 'Approved')
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                                        ✅ Approved
                                    </span>
                                @elseif ($row->decision === 'Rejected')
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700">
                                        ❌ Rejected
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-700">
                                        ⏳ En attente
                                    </span>
                                @endif
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
