<div>
    <x-table title="List of car request" :addbtn="false">
        <x-slot:addcreate>
            <a href="{{ route('car.create') }}" role="button" class="btn btn-primary">
                <i class='me-1 bx bx-plus-circle'></i> New
            </a>
        </x-slot:addcreate>
        <x-slot:filter>
            @if(!empty($selectedRows))
            <div class="col-md-4">
                <div class="mb-3">
                    <button class="btn btn-danger" wire:click="bulkAction('reject','car')" @if(empty($selectedRows))
                        disabled @endif wire:loading.attr="disabled" wire:target="bulkAction">
                        <span wire:loading.remove wire:target="bulkAction">Reject</span>
                        <span wire:loading wire:target="bulkAction">
                            <i class="bx bx-loader-alt fa-spin"></i> Traitement...
                        </span>
                    </button>
                    <button class="btn btn-success" wire:click="bulkAction('approve','car')" @if(empty($selectedRows))
                        disabled @endif wire:loading.attr="disabled" wire:target="bulkAction">
                        <span wire:loading.remove wire:target="bulkAction">Approved</span>
                        <span wire:loading wire:target="bulkAction">
                            <i class="bx bx-loader-alt fa-spin"></i> Traitement...
                        </span>
                    </button>
                </div>
            </div>
            @endif
            @if (Auth::user()->isGm() || Auth::user()->isAdmin())
            <div class="col-sm-6 col-md-3">
                <div wire:ignore>
                    <x-select label="Filter by Compagny" wire:model.live='compagny'>
                        @foreach ($compagnies as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    </x-select>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div wire:ignore>
                    <x-select label="Filter by Department" wire:model.live='department'>
                        @foreach ($departments as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    </x-select>
                </div>
            </div>
            @endif
            <div class="col-sm-6 col-md-3">
                <div wire:ignore>
                    <x-select label="Filter by Status" wire:model.live='status'>
                        @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                        <option value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                    </x-select>
                </div>
            </div>
        </x-slot:filter>
        <thead class="text-xs uppercase bg-gray-200 text-gray-600">
            <tr>
                <th class="px-4 py-3">
                    <input type="checkbox" wire:click="selectAll" wire:model="selectedRows" id="select-all">
                </th>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Reference</th>
                <th class="px-4 py-3">Compagny</th>
                <th class="px-4 py-3">Department</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">HOD Approval</th>
                <th class="px-4 py-3">GM Approval</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Created Date</th>
                <th class="px-4 py-3">Action</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($this->rows as $row)
            <tr wire:key="row-{{ $row->id }}" @class(['table-primary'=> in_array($row->id, $selectedRows)
                ])>
                <td class="px-4 py-3">
                    <input type="checkbox" wire:model.live="selectedRows" value="{{ $row->id }}">
                </td>
                <td class="px-4 py-3">{{ $row->id }}</td>
                <td class="px-4 py-3">{{ $row->reference }}</td>
                <td class="px-4 py-3">{{ $row->user->compagnie->name }}</td>
                <td class="px-4 py-3">{{ $row->user->department->name }}</td>
                <td class="px-4 py-3">{{ $row->user->name }}</td>
                <td class="px-4 py-3">{{ $row->hod_approval_view() }}</td>
                <td class="px-4 py-3">{{ $row->gm_approval_view() }}</td>
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
                <td class="px-4 py-3">{{ $row->created_at }}</td>
                <td class="px-4 py-3">
                    <x-button-edit href="{{ route('car.edit', ['car' => $row]) }}" :row="$row" />
                    <x-button-show href="{{ route('car.show', ['car' => $row]) }}" :row="$row" />
                    <x-button-delete url="{{ url('car/' . $row->id) }}" :row="$row" />
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