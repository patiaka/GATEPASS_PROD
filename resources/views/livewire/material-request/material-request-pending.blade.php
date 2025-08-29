<div>
    <x-table title="List of pending material request" :addbtn="false">
        <x-slot:filter>
            <div class="flex flex-wrap gap-4 items-end">

                {{-- Bulk Actions --}}
                @if(!empty($selectedRows))
                <div class="flex gap-2">
                    <button class="btn btn-danger" wire:click="bulkAction('reject','material')"
                        wire:loading.attr="disabled" wire:target="bulkAction">
                        <span wire:loading.remove wire:target="bulkAction">Reject</span>
                        <span wire:loading wire:target="bulkAction">
                            <i class="bx bx-loader-alt fa-spin"></i> Processing...
                        </span>
                    </button>

                    <button class="btn btn-success" wire:click="bulkAction('approve','material')"
                        wire:loading.attr="disabled" wire:target="bulkAction">
                        <span wire:loading.remove wire:target="bulkAction">Approve</span>
                        <span wire:loading wire:target="bulkAction">
                            <i class="bx bx-loader-alt fa-spin"></i> Processing...
                        </span>
                    </button>
                </div>
                @endif

                {{-- Department Filter --}}
                {{-- @if (Auth::user()->isGm() || Auth::user()->isAdmin())
                <div class="w-full sm:w-48">
                    <x-select label="Filter by Department" name="department" wire:model.live="department">
                        <option value="">All Departments</option>
                        @foreach ($departments as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                @endif --}}

                {{-- Status Filter --}}
                {{-- <div class="w-full sm:w-48">
                    <x-select label="Filter by Status" wire:model.live="status">
                        <option value="">All Statuses</option>
                        @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                        <option value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                    </x-select>
                </div> --}}
            </div>
        </x-slot:filter>

        <thead class="bg-gray-200 uppercase">
            <tr>
                {{-- <th class="px-4 py-3">
                    <input type="checkbox" wire:click="selectAll" wire:model="selectedRows" id="select-all">
                </th> --}}
                <th class="px-4 py-3 text-left text-sm font-medium">ID</th>
                <th class="px-4 py-3 text-left text-sm font-medium">reference</th>
                <th class="px-4 py-3 text-left text-sm font-medium">Date</th>
                <th class="px-4 py-3 text-left text-sm font-medium">Company</th>
                <th class="px-4 py-3 text-left text-sm font-medium">Department</th>
                @if (Auth::user()->isGm() || Auth::user()->isHod())
                <th class="px-4 py-3 text-left text-sm font-medium">Requestor</th>
                @endif
                <th class="px-4 py-3 text-left text-sm font-medium">stat</th>
                <th class="px-4 py-3 text-left text-sm font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 bg-white">
            @forelse ($this->rows as $row)
            <tr wire:key="row-{{ $row->id }}">
                {{-- <td class="px-4 py-3">
                    <input type="checkbox" wire:model.live="selectedRows" value="{{ $row->id }}">
                </td> --}}
                <td class="px-4 py-3">{{ $row->id }}</td>
                <td class="px-4 py-3">{{ $row->reference }}</td>
                <td class="px-4 py-3">{{ $row->created_at }}</td>
                <td class="px-4 py-3">{{ $row->company }}</td>
                <td class="px-4 py-3">{{ $row->user->department->name }}</td>

                @if (Auth::user()->isGm() || Auth::user()->isHod())
                <td class="px-4 py-3 text-sm">
                    <x-form-request wire:key="request-{{ $row->id }}" :model="$row" type="material" />
                </td>
                @endif
                <td class="px-4 py-3">
                    <span @class(['btn badge rounded-pill btn-sm' ,'bg-primary'=> $row->isApproved(),
                        'bg-danger' => $row->isRejected(),
                        'bg-danger' => $row->isExpired(),
                        'bg-info' => $row->isPending(),
                        'bg-warning' => $row->isProgress()
                        ])>
                        {{ $row->status }}
                    </span>

                </td>
                <td class="px-4 py-3">
                    <x-button-edit href="{{ route('material.edit', ['MaterialRequest' => $row]) }}" :row="$row" />
                    <x-button-show href="{{ route('material.show', ['MaterialRequest' => $row]) }}" :row="$row" />
                    <x-button-delete url="{{ url('material/' . $row->id) }}" :row="$row" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">No result</td>
            </tr>
            @endforelse
        </tbody>

    </x-table>
</div>