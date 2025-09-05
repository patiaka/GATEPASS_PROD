<div>
    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4 mb-4">
        <h1 class="font-bold text-xl text-[#134169]">Vehicle Check In / Out</h1>
        <button @click="$store.modal.open('security-check')"
            class="text-blue-600 border border-blue-600 px-3 py-1 rounded hover:bg-blue-600 hover:text-white">
            New Check In
        </button>

    </div>
    <div class="flex flex-wrap gap-4 items-end mb-4">
        <div class="w-full sm:w-48">
            <div class="relative flex items-center">
                <span class="absolute left-3 text-gray-400">
                    <i data-lucide="search"></i>
                </span>
                <input wire:model.live.debounce.100ms='search' type="text"
                    class="w-full pl-10 pr-4 py-2 border rounded-md" placeholder="Search...">
            </div>
        </div>
        <div class="w-full sm:w-48">
            <x-select label="Filter by Department" name="department" wire:model.live="department">
                <option value="">All Departments</option>
                @foreach ($departments as $row)
                <option value="{{ $row->id }}">{{ $row->name }}</option>
                @endforeach
            </x-select>
        </div>

        <div class="w-full sm:w-48">
            <x-input type="date" wire:model.live="date" label="Date" />
        </div>
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
                    <th class="px-4 py-3 text-left text-sm font-medium">Vehicle</th>
                    {{-- <th class="px-4 py-3 text-left text-sm font-medium">Vehicle type</th> --}}
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
                    <td class="px-4 py-3 text-sm">#{{ $row->requestable->car_number }}</td>
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
    </div>

    <!-- Modal -->
    <x-modal name="security-check" title="Check in Response Form">
        <!-- Form -->
        <form wire:submit.prevent="recordSecurityCheck">
            <div class="space-y-4">
                {{-- action --}}
                <div>
                    <label class="block text-sm font-medium mb-1">gate pass list</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2" wire:model="car_request_id">
                        <option value="" selected>select</option>
                        @foreach ($carRequests as $row)
                        <option value="{{ $row->id }}">{{ $row->reference }}</option>
                        @endforeach
                    </select>
                    @error('car_request_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
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