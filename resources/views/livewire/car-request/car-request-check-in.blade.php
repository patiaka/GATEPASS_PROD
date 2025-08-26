<div>
    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4 mb-4">
        <h1 class="font-bold text-xl text-[#134169]">Visitor Check In / Out</h1>
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
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium">ID</th>
                    <th class="px-4 py-3 text-left text-sm font-medium">Date</th>
                    <th class="px-4 py-3 text-left text-sm font-medium">Department</th>
                    <th class="px-4 py-3 text-left text-sm font-medium">Requestor Name</th>
                    <th class="px-4 py-3 text-left text-sm font-medium">Requestor Company</th>
                    <th class="px-4 py-3 text-left text-sm font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($this->rows as $row)
                <tr wire:key="row-{{ $row->id }}">

                    <td class="px-4 py-3 text-sm">#{{ $row->reference }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row->created_at }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row->user->department->name }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row->user->name }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row->company }}</td>
                    <td class="px-4 py-3 text-sm">VisiCorp</td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="h-3 w-3 bg-green-400 rounded-full"></span>
                            <span class="text-xs text-green-700">Completed</span>
                        </div>
                        <button
                            class="inline-flex items-center gap-1 text-sm px-3 py-1 border rounded text-blue-600 border-blue-600 hover:bg-blue-600 hover:text-white">
                            <!-- Check in icon (optional SVG) -->
                            <span>Check In</span>
                        </button>
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
</div>
