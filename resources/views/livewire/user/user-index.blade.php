<div>
    <x-table title="List of users" :addbtn="false">

        <x-slot:addcreate>
            <x-button-add link="{{ route('user.create') }}" />
        </x-slot:addcreate>
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
                <th class="px-4 py-3">invite</th>
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

                <td>
                    <button wire:click='invite_user({{ $row }})' wire:target="invite_user({{ $row }})"
                        class="rounded-md bg-[#0e3a61] px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-blue-500 sm:ml-3 sm:w-auto">
                        <span wire:loading wire:target="invite_user({{ $row }})">
                            <span class="iconify lucide--loader size-4"></span> Processing...
                        </span>
                        invite user
                    </button>
                </td>
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



</div>