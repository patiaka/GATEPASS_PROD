<div>
    <x-table title="List of users">

        <x-slot:filter>
            <div class="flex flex-wrap gap-4 items-end">
                {{-- Department Filter --}}

                <div class="w-full sm:w-48">
                    <x-select label="Filter by Department" name="department" wire:model.live="department">
                        <option value="">All Departments</option>
                        @foreach ($departments as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    </x-select>
                </div>

                {{-- Status Filter --}}
                <div class="w-full sm:w-48">
                    <x-select label="Filter by Status" wire:model.live="status">
                        <option value="">All Roles</option>
                        @foreach (App\Enum\RoleEnum::cases() as $row)
                        <option value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                    </x-select>
                </div>
            </div>
        </x-slot:filter>
        <thead class="text-xs uppercase bg-gray-200 text-gray-600">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Department</th>
                <th class="px-4 py-3">Email/nom</th>
                <th class="px-4 py-3">Position</th>
                <th class="px-4 py-3">role</th>
                <th class="px-4 py-3">status</th>
                <th class="px-4 py-3">change MDP</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Action</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($this->rows as $row)
            <tr wire:key='user-{{ $row->id }}' class="border-b hover:bg-gray-100">
                <td class="px-4 py-3">{{ $row->id }}</td>
                <td class="px-4 py-3">{{ $row->department->name }}</td>
                <td class="px-4 py-3">{{ $row->email }}<br>{{ $row->name }}</td>
                <td class="px-4 py-3">{{ $row->poste }}</td>
                <td class="px-4 py-3">{{ $row->role }}</td>
                <td class="px-4 py-3">

                    <span @class(['btn badge rounded-pill btn-sm' ,'bg-primary'=> $row->status == 1,
                        'bg-danger' => $row->status == 0,
                        ])>
                        {{ $row->status ? 'Active':'Inactive' }}
                    </span>
                </td>
                <td class="px-4 py-3">{{ $row->change_password ? 'OUI':'NON' }}</td>

                <td class="px-4 py-3">{{ $row->created_at }}</td>
                <td class="px-4 py-3 flex">
                    <x-button-edit href="{{ route('user.edit', ['user' => $row]) }}" />
                    <x-button-delete rowId="{{ $row->id }}" />
                </td>
            </tr>
            @empty
            <h2 class="text-center">No result</h2>
            @endforelse
        </tbody>
    </x-table>
    <x-modal title="Form of new user">
        <x-form route="save">
            <div class="">
                <x-input type="text" name="name" place="name" wire:model='form.name' />
                <x-input type="email" name="email" place="email" wire:model='form.email' />
                <x-input type="text" name="poste" place="poste" wire:model='form.poste' />
                <x-select label="Department" name="department" wire:model='form.department_id'>
                    @foreach ($departments as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </x-select>

                <x-select label="Role" wire:model='form.role'>
                    @foreach (App\Enum\RoleEnum::cases() as $row)
                    <option value="{{ $row }}">{{ $row }}</option>
                    @endforeach
                </x-select>
            </div>
        </x-form>
    </x-modal>


</div>