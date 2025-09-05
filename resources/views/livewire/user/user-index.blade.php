<div>
    <x-table title="List of users" :addbtn="false">

        <x-slot:addcreate>
            <x-button-add link="{{ route('user.create') }}" />
        </x-slot:addcreate>

        <x-slot:filter>
            <div class="flex flex-wrap gap-4 items-end">
                {{-- Department Filter --}}
                <div class="w-full sm:w-52">
                    <x-select label="Filter by Department" name="department" wire:model.live="department">
                        <option value="">All Departments</option>
                        @foreach ($departments as $row)
                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    </x-select>
                </div>

                {{-- Status/Role Filter --}}
                <div class="w-full sm:w-52">
                    <x-select label="Filter by Status" wire:model.live="role">
                        <option value="">All Roles</option>
                        @foreach (App\Enum\RoleEnum::cases() as $row)
                            <option value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                    </x-select>
                </div>
            </div>
        </x-slot:filter>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full text-sm border-collapse">
                <!-- En-tête -->
                <thead class="text-[11px] uppercase text-white bg-[#0e3a61] sticky top-0 shadow z-10">
                    <tr>
                        <th scope="col" class="px-3 py-2 text-left">ID</th>
                        <th scope="col" class="px-3 py-2 text-left">Department</th>
                        <th scope="col" class="px-3 py-2 text-left">Email / Name</th>
                        <th scope="col" class="px-3 py-2 text-left">Position</th>
                        <th scope="col" class="px-3 py-2 text-left">Role</th>
                        <th scope="col" class="px-3 py-2 text-left">Delegated role</th>
                        <th scope="col" class="px-3 py-2 text-left">Status</th>
                        <th scope="col" class="px-3 py-2 text-left">Change MDP</th>
                        <th scope="col" class="px-3 py-2 text-left">Invite</th>
                        <th scope="col" class="px-3 py-2 text-left">Date</th>
                        <th scope="col" class="px-3 py-2 text-left">Action</th>
                    </tr>
                </thead>

                <!-- Corps du tableau -->
                <tbody class="divide-y divide-gray-200">
                    @forelse ($this->rows as $row)
                        <tr wire:key="user-{{ $row->id }}" class="hover:bg-gray-100 even:bg-gray-50 odd:bg-white">
                            <td class="px-3 py-2 whitespace-nowrap">{{ $row->id }}</td>

                            <td class="px-3 py-2 max-w-[200px]">
                                <span class="line-clamp-1" title="{{ $row->department->name }}">
                                    {{ $row->department->name }}
                                </span>
                            </td>

                            <td class="px-3 py-2">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900 truncate max-w-[220px]" title="{{ $row->email }}">{{ $row->email }}</span>
                                    <span class="text-gray-500 truncate max-w-[220px]" title="{{ $row->name }}">{{ $row->name }}</span>
                                </div>
                            </td>

                            <td class="px-3 py-2">
                                <span class="truncate block max-w-[200px]" title="{{ $row->poste }}">
                                    {{ $row->poste }}
                                </span>
                            </td>

                            <td class="px-3 py-2">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $row->role }}
                                </span>
                            </td>

                            <td class="px-3 py-2">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                    {{ $row->delegated_role }}
                                </span>
                            </td>

                            <td class="px-3 py-2">
                                <span
                                    @class([
                                        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold border',
                                        'bg-emerald-50 text-emerald-700 border-emerald-200' => $row->status == 1,
                                        'bg-rose-50 text-rose-700 border-rose-200' => $row->status == 0,
                                    ])>
                                    {{ $row->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td class="px-3 py-2">
                                <span
                                    @class([
                                        'inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium',
                                        'bg-emerald-50 text-emerald-700 border border-emerald-200' => $row->change_password,
                                        'bg-gray-50 text-gray-600 border border-gray-200' => ! $row->change_password,
                                    ])>
                                    {{ $row->change_password ? 'OUI' : 'NON' }}
                                </span>
                            </td>

                            <td class="px-3 py-2">
                                <button
                                    wire:click="invite_user({{ $row }})"
                                    wire:target="invite_user({{ $row }})"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-2 rounded-md bg-[#0e3a61] px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 disabled:opacity-60 disabled:cursor-not-allowed">
                                    <span wire:loading wire:target="invite_user({{ $row }})" class="inline-flex items-center gap-1">
                                        <svg class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25" stroke-width="4"/>
                                            <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4"/>
                                        </svg>
                                        Processing...
                                    </span>
                                    <span wire:loading.remove wire:target="invite_user({{ $row }})">
                                        invite user
                                    </span>
                                </button>
                            </td>

                            <td class="px-3 py-2 whitespace-nowrap text-gray-700">
                                {{ $row->created_at }}
                            </td>

                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <x-button-edit href="{{ route('user.edit', ['user' => $row]) }}" />
                                    <x-button-delete rowId="{{ $row->id }}" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-3 py-6 text-center text-gray-500">
                                No result
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </x-table>
</div>
