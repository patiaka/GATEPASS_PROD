<div>
    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4 mb-4">
        <h1 class="font-bold text-xl text-[#134169]">Material Check In / Out</h1>

        <button @click="$store.modal.open('security-check')"
            class="text-blue-600 border border-blue-600 px-3 py-1 rounded hover:bg-blue-600 hover:text-white">
            New Check In
        </button>



    </div>
    <div class="flex flex-wrap gap-4 items-end mb-4">
        <div class="w-full sm:w-64">
            <div class="relative flex items-center">
                <span class="absolute left-3 text-gray-400">
                    <i data-lucide="search"></i>
                </span>
                <input wire:model.live.debounce.100ms='search' type="text"
                    class="w-full pl-10 pr-4 py-2 border rounded-md" placeholder="Search...">
            </div>
        </div>
        <div class="w-full sm:w-64">
            <x-select label="Filter by Department" name="department" wire:model.live="department">
                <option value="">All Departments</option>
                @foreach ($departments as $row)
                <option value="{{ $row->id }}">{{ $row->name }}</option>
                @endforeach
            </x-select>
        </div>

        <div class="w-full sm:w-80">
            <x-input type="date" wire:model.live="date" label="Date" />
        </div>
        @if ($date)

        <div class="w-full sm:w-48">

            <!-- Download Button -->
            <button wire:click="export" wire:loading.attr="disabled" wire:target="export"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm flex items-center gap-2">

                <!-- Download icon (when not loading) -->
                <span wire:loading.remove wire:target="export" class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 4v12" />
                    </svg>
                    Export
                </span>

                <!-- Loading icon (spinner) -->
                <span wire:loading wire:target="export" class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m0 14v1m8-8h1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m0 12.728l.707-.707M17.657 6.343l.707-.707" />
                    </svg>
                    Processing...
                </span>
            </button>
        </div>
        @endif
    </div>
    <!-- Table Card -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#134169] text-white">
                <tr class="uppercase">
                    <th class="px-4 py-3 text-left text-sm font-medium">ID</th>
                    <th class="px-4 py-3 text-left text-sm font-medium">Date</th>
                    <th class="px-4 py-3 text-left text-sm font-medium">Department</th>
                    <th class="px-4 py-3 text-left text-sm font-medium">Requestor Name</th>
                    <th class="px-4 py-3 text-left text-sm font-medium">Requestor Company</th>
                    <th class="px-4 py-3 text-left text-sm font-medium">action</th>
                    <th class="px-4 py-3 text-left text-sm font-medium">decision</th>
                    <th class="px-4 py-3 text-left text-sm font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($this->rows as $row)
                <tr wire:key="row-{{ $row->id }}">

                    <td class="px-4 py-3 text-sm">#{{ $row->requestable->reference }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row->created_at }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row->user->department->name }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row->user->name }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row->requestable->company }}</td>
                    {{-- <td class="px-4 py-3 text-sm">#{{ $row->requestable->car_number }}</td> --}}
                    {{-- <td class="px-4 py-3 text-sm">#{{ $row->requestable->car_type }}</td> --}}
                    <td class="px-4 py-3 text-sm">{{ $row->action }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if ($row->decision === 'Approved')
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            ✅ Approved
                        </span>
                        @elseif ($row->decision === 'Rejected')
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            ❌ Rejected
                        </span>
                        @else
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            ⏳ En attente
                        </span>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="h-3 w-3 bg-green-400 rounded-full"></span>
                            <span class="text-xs text-green-700">Completed</span>
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="9" class="text-center">No result</td>
                </tr>
                @endforelse

                <!-- More rows as needed... -->
            </tbody>
        </table>

        @if($this->rows)
        <div class="p-4">
            {{ $this->rows->links() }}
        </div>
        @endif
    </div>

    <!-- Modal -->
    <x-modal name="security-check" title="Check in Response Form">
        <!-- Form -->
        <form wire:submit.prevent="recordSecurityCheck">
            <div class="space-y-4">
                {{-- action --}}
                <div>
                    <x-select2 :options="$materialRequests" wire:model="material_request_id" name="material_request_id"
                        placeholder="Select gate pass" label="material request list" />
                    @error('material_request_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- action --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Action</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2" wire:model="action">
                        <option value="" selected>select</option>
                        <option value="Exit">Exit</option>
                        <option value="Entry">Entry</option>
                    </select>
                    @error('action') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Decision --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Decision</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2" wire:model="decision">
                        <option value="" selected>select</option>
                        @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                        @continue(in_array($row->value, ["Progress", "Pending", "Expired"]))
                        <option value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                    </select>
                    @error('decision') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" @click="$store.modal.close('security-check')"
                    class="px-4 py-2 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-100">
                    Cancel
                </button>
                <button type="submit" wire:loading.attr="disabled" wire:target="recordSecurityCheck"
                    class="px-4 py-2 rounded-md bg-[#134169] text-white hover:bg-red-500">
                    <span wire:loading.remove wire:target="recordSecurityCheck">
                        Approve
                    </span>
                    <span wire:loading wire:target="recordSecurityCheck">
                        <i class="bx bx-loader-alt fa-spin"></i> Processing...
                    </span>
                </button>
            </div>
        </form>
    </x-modal>