<div>
    <x-table title="List of material request" :addbtn="false">
        <x-slot:addcreate>
            <a href="{{ route('material.create') }}" role="button" class="btn btn-primary">
                <i class='me-1 fa fa-plus-circle'></i> Nouveau
            </a>
        </x-slot:addcreate>
        <x-slot:filter>

            <div class="col-sm-6 col-md-3">
                <x-input-group type="text" wire:model.live="search" label="Search" />
            </div>
            <div class="col-sm-6 col-md-3">
                <x-select-group label="Department" wire:model.live='department'>
                    @foreach ($departments as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </x-select-group>
            </div>
            <div class="col-sm-6 col-md-3">
                <x-select-group label="User" wire:model.live='user'>
                    @foreach ($users as $row)
                    <option value="{{ $row->id }}">{{ $row->name }} <br>{{ $row->email }}</option>
                    @endforeach
                </x-select-group>
            </div>
            <div class="col-sm-6 col-md-3">
                <x-select-group label="Status" wire:model.live='status'>
                    @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                    <option value="{{ $row }}">{{ $row }}</option>
                    @endforeach
                </x-select-group>
            </div>
            <div class="col-md-5">
                <hr>
                <h3>apply Action</h3>
                <div class="mb-3">
                    <x-select-group label="Status" wire:model='bulkAction'>
                        @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                        <option value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                    </x-select-group>

                    <button class="btn btn-primary btn-sm" wire:click="applyAction" {{ count($selectedRows)===0
                        ? 'disabled' : '' }}>
                        Appliquer
                    </button>
                </div>
            </div>

        </x-slot:filter>
        <thead>
            <tr>
                <th>
                    <input type="checkbox" wire:model="selectAll">
                </th>
                <th>ID</th>
                <th>Reference</th>
                <th>Email/nom</th>
                <th>GM Approval</th>
                <th>HOD approval</th>
                <th>Statuts</th>
                <th>Date creation</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($this->rows as $row)

            <tr wire:key='{{ $row->id }}'>
                <td>
                    <input type="checkbox" wire:model="selectedRows" value="{{ $row->id }}">
                </td>
                <td>{{ $row->id }}</td>
                <td>{{ $row->reference }}</td>
                <td>{{ $row->user->email }}<br>{{ $row->user->name }}</td>
                <td>{{ $row->gm_approval_view() }}</td>
                <td>{{ $row->hod_approval_view() }}</td>
                <td>

                    <div class="dropdown action-label">
                        <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa fa-dot-circle-o text-success"></i> {{ $row->status }}
                        </a>
                        <div class="dropdown-menu" style="">

                            <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-success"></i> Active</a>
                            <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-danger"></i>
                                Inactive</a>
                        </div>
                    </div>
                </td>
                <td>{{ $row->created_at }}</td>
                <td>
                    <x-button-edit href="{{ route('material.edit', ['material' => $row]) }}" />
                    <x-button-show href="{{ route('material.show', ['material' => $row]) }}" />
                    <x-button-delete url="{{ url('material/'.$row->id) }}" />
                </td>
            </tr>
            @empty
            <h2 class="text-center">No result</h2>
            @endforelse
        </tbody>
    </x-table>

</div>