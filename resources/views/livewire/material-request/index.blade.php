<div>
    <x-table title="List of material request" :addbtn="false">
        <x-slot:addcreate>
            <a href="{{ route('material.create') }}" role="button" class="btn btn-primary">
                <i class='me-1 bx bx-plus-circle'></i> New
            </a>
        </x-slot:addcreate>
        <x-slot:filter>
            @if(!empty($selectedRows))
            <div class="col-md-4">
                <div class="mb-3">
                    <button class="btn btn-danger" wire:click="bulkAction('reject')" @if(empty($selectedRows)) disabled
                        @endif>
                        Reject
                    </button>
                    <button class="btn btn-success" wire:click="bulkAction('approve')" @if(empty($selectedRows))
                        disabled @endif>
                        Approved
                    </button>
                </div>
            </div>
            @endif
            @if (Auth::user()->isGm() || Auth::user()->isAdmin())
            <div class="col-sm-6 col-md-3">
                <x-select label="Department" wire:model.live='department'>
                    @foreach ($departments as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </x-select>
            </div>
            @endif
            <div class="col-sm-6 col-md-3">
                <x-select label="Filter by Status" wire:model.live='status'>
                    @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                    <option value="{{ $row }}">{{ $row }}</option>
                    @endforeach
                </x-select>
            </div>


        </x-slot:filter>
        <thead>
            <tr>
                <th>
                    <input type="checkbox" wire:click="selectAll" wire:model="selectedRows" id="select-all">
                </th>
                <th>ID</th>
                <th>Reference</th>
                <th>Compagny</th>
                <th>Department</th>
                <th>Name</th>
                <th>HOD Approval</th>
                <th>GM Approval</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($this->rows as $row)
            <tr wire:key="row-{{ $row->id }}" @class(['table-primary'=> in_array($row->id, $selectedRows)
                ])>
                <td>
                    <input type="checkbox" wire:model.live="selectedRows" value="{{ $row->id }}">
                </td>
                <td>{{ $row->id }}</td>
                <td>{{ $row->reference }}</td>
                <td>{{ $row->user->compagnie->name }}</td>
                <td>{{ $row->user->department->name }}</td>
                <td>{{ $row->user->name }}</td>
                <td>{{ $row->hod_approval_view() }}</td>
                <td>{{ $row->gm_approval_view() }}</td>
                <td>
                    <span @class(['btn badge rounded-pill btn-sm' ,'bg-primary'=> $row->isApproved(),
                        'bg-danger' => $row->isRejected(),
                        'bg-info' => $row->isPending(),
                        'bg-warning' => $row->isProgress()
                        ])>
                        {{ $row->status }}
                    </span>

                </td>
                <td>{{ $row->created_at }}</td>
                <td>

                    <x-button-edit href="{{ route('material.edit', ['material' => $row]) }}" />
                    <x-button-show href="{{ route('material.show', ['material' => $row]) }}" />
                    <x-button-delete url="{{ url('material/' . $row->id) }}" />
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
<script>
    $(".select2").each(function () {
            var current = $(this);
            current.wrap('<div class="position-relative"></div>').select2({
                placeholder: "Selectionner",
                dropdownParent: current.parent(),
            });
            // Get the Livewire property name from the wire:model attribute
            var propertyName = current.attr('wire:model.live');
            // Listen for change event and update Livewire property
            current.on('change', function (e) {
            // Add opacity to table
            $('.table-responsive').addClass('opacity-50');

            @this.set(propertyName, $(this).val()).then(() => {
                // Remove opacity after Livewire updates
                $('.table-responsive').removeClass('opacity-50');
            });
            });
        });
</script>