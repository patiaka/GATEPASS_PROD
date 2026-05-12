<div>
    <x-table title="Resident & Vehicle offsite Approval" :addbtn="false" :rows="$this->rows">
        <x-slot:filter>
            <div class="flex flex-wrap gap-4 items-end">

                {{-- Bulk Actions --}}
                @if(!empty($selectedRows))
                <div class="flex gap-2">
                    <button class="btn btn-danger" wire:click="bulkAction('reject','car')" wire:loading.attr="disabled"
                        wire:target="bulkAction">
                        <span wire:loading.remove wire:target="bulkAction">Reject</span>
                        <span wire:loading wire:target="bulkAction">
                            <i class="bx bx-loader-alt fa-spin"></i> Processing...
                        </span>
                    </button>

                    <button class="btn btn-success" wire:click="bulkAction('approve','car')"
                        wire:loading.attr="disabled" wire:target="bulkAction">
                        <span wire:loading.remove wire:target="bulkAction">Approve</span>
                        <span wire:loading wire:target="bulkAction">
                            <i class="bx bx-loader-alt fa-spin"></i> Processing...
                        </span>
                    </button>
                </div>
                @endif

                {{-- Department Filter --}}
                @if (Auth::user()->isGm() || Auth::user()->isAdmin())
                <div class="w-full sm:w-48">
                    <x-select label="Filter by Department" name="department" wire:model.live="department">
                        <option value="">All Departments</option>
                        @foreach ($departments as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                @endif

                {{-- Status Filter --}}
                <div class="w-full sm:w-48">
                    <x-select label="Filter by Status" wire:model.live="status">
                        <option value="">All Statuses</option>
                        @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                        <option value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                    </x-select>
                </div>



            </div>
        </x-slot:filter>

        <!-- THEAD -->
        <thead class="sticky top-0 z-20">
            <tr class="uppercase tracking-wide text-[12px] bg-slate-100 text-slate-700 border-b">
                {{-- <th class="px-4 py-3">
                    <input type="checkbox" wire:click="selectAll" wire:model="selectedRows" id="select-all">
                </th> --}}
                {{-- <th class="px-4 py-2 text-left font-semibold">ID</th> --}}
                <th class="px-4 py-2 text-left font-semibold">reference</th>
                <th class="px-4 py-2 text-left font-semibold">Date</th>
                <th class="px-4 py-2 text-left font-semibold">Company</th>
                <th class="px-4 py-2 text-left font-semibold">Department</th>
                {{-- @if (Auth::user()->isGm() || Auth::user()->isHod())
                <th class="px-4 py-3 text-left font-medium">Requestor</th>
                @endif --}}

                <th class="px-4 py-2 text-left font-semibold">Actions</th>
            </tr>
        </thead>

        <!-- TBODY -->
        <tbody class="divide-y divide-gray-100 bg-white">
            @forelse ($this->rows as $row)
            <tr wire:key="row-{{ $row->id }}">
                {{-- <td class="px-4 py-3">
                    <input type="checkbox" wire:model.live="selectedRows" value="{{ $row->id }}">
                </td> --}}
                {{-- <td class="px-4 py-2">{{ $row->id }}</td> --}}

                <td class="px-4 py-2">
                    <a href="{{ route('car.show', ['CarRequest' => $row]) }}"
                        class="inline-flex items-center gap-1.5 rounded-md bg-indigo-50 px-2 py-1
                        text-[11px] font-semibold text-indigo-700
                        ring-1 ring-inset ring-indigo-200
                        hover:bg-indigo-100 hover:text-indigo-800 transition">
                        {{ $row->reference }}
                    </a>
                </td>

                <td class="px-4 py-2">{{ $row->created_at }}</td>
                <td class="px-4 py-2">{{ $row->company }}</td>
                <td class="px-4 py-2">{{ $row->user->department->name }}</td>

                <td class="px-4 py-3 flex items-center gap-2">
                    <x-button-edit href="{{ route('car.edit', ['CarRequest' => $row]) }}" :row="$row" />
                    <x-button-show href="{{ route('car.show', ['CarRequest' => $row]) }}" :row="$row" />
                    <x-button-delete :row="$row" />

                    @if (Auth::user()->canApprove($row) && Auth::user()->isApprover())
                        <x-form-request wire:key="request-{{ $row->id }}" :model="$row" type="vehicle" />
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-4">You have no pending approval requests</td>
            </tr>
            @endforelse
        </tbody>

    </x-table>
</div>