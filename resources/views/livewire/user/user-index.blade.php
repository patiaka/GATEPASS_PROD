<div>
    <x-table title="Users Database" :addbtn="false" :rows="$this->rows">

        <x-slot:addcreate>
            <x-button-add link="{{ route('user.create') }}" />
        </x-slot:addcreate>

        <x-slot:filter>
            <div class="flex flex-wrap gap-4 items-end">
                <div class="w-full sm:w-70">
                    <x-select label="Filter by Department" name="department" wire:model.live="department">
                        <option value="">All Departments</option>
                        @foreach ($departments as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    </x-select>
                </div>

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

        <!-- THEAD -->
        <thead class="uppercase bg-slate-100 text-slate-700 text-[12px] sticky top-0 shadow-sm z-10">
            <tr>
                <th class="px-3 py-2 text-left font-semibold">ID</th>
                <th class="px-3 py-2 text-left font-semibold">Department</th>
                <th class="px-3 py-2 text-left font-semibold">Email / Name</th>
                <th class="px-3 py-2 text-left font-semibold">Position</th>
                <th class="px-3 py-2 text-left font-semibold">Role</th>
                <th class="px-3 py-2 text-left font-semibold">Delegated</th>
                <th class="px-3 py-2 text-left font-semibold">Status</th>
                <th class="px-3 py-2 text-left font-semibold">Change PWD</th>
                <th class="px-3 py-2 text-left font-semibold">Invite</th>
                <th class="px-3 py-2 text-left font-semibold">Date</th>
                <th class="px-3 py-2 text-center font-semibold">Action</th>
            </tr>
        </thead>

        <!-- TBODY -->
        <tbody class="divide-y divide-gray-100">
            @forelse ($this->rows as $row)
            <tr wire:key="user-{{ $row->id }}" class="hover:bg-slate-50 transition even:bg-gray-50/40">

                <td class="px-3 py-2 font-medium text-gray-700">
                    {{ $row->id }}
                </td>

                <td class="px-3 py-2 max-w-[200px] text-gray-700">
                    <span class="line-clamp-1" title="{{ $row->department->name }}">
                        {{ $row->department->name }}
                    </span>
                </td>

                <td class="px-3 py-2">
                    <div class="flex flex-col">
                        <span class="font-medium text-gray-800 truncate max-w-[220px]"
                            title="{{ $row->email }}">
                            {{ $row->email }}
                        </span>
                        <span class="text-gray-500 truncate max-w-[220px]" title="{{ $row->name }}">
                            {{ $row->name }}
                        </span>
                    </div>
                </td>

                <td class="px-3 py-2 text-gray-700">
                    <span class="truncate block max-w-[200px]" title="{{ $row->poste }}">
                        {{ $row->poste }}
                    </span>
                </td>

                <!-- Role -->
                <td class="px-3 py-2">
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold
                                    bg-blue-50 text-blue-700 border border-blue-200">
                        {{ $row->role }}
                    </span>
                </td>

                <!-- Delegated role -->
                <td class="px-3 py-2">
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold
                                    bg-purple-50 text-purple-700 border border-purple-200">
                        {{ $row->delegated_role }}
                    </span>
                </td>

                <!-- Status -->
                <td class="px-3 py-2">
                    <span
                        @class([ 'inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold border'
                        , 'bg-emerald-50 text-emerald-700 border-emerald-200'=> $row->status == 1,
                        'bg-rose-50 text-rose-700 border-rose-200' => $row->status == 0,
                        ])>
                        {{ $row->status ? 'Active' : 'Inactive' }}
                    </span>
                </td>

                <!-- Change password -->
                <td class="px-3 py-2">
                    <span
                        @class([ 'inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold border'
                        , 'bg-emerald-50 text-emerald-700 border-emerald-200'=>
                        $row->change_password,
                        'bg-gray-50 text-gray-600 border border-gray-200' => !$row->change_password,
                        ])>
                        {{ $row->change_password ? 'YES' : 'NO' }}
                    </span>
                </td>

                <!-- Invite -->
                <td class="px-3 py-2">
                    <button wire:click="invite_user({{ $row }})" wire:target="invite_user({{ $row }})"
                        wire:loading.attr="disabled" class="inline-flex items-center gap-1.5 rounded-md
                                    bg-[#134169] px-2 py-1 text-[10px] font-semibold text-white
                                    shadow-sm hover:bg-blue-700 focus:outline-none
                                    focus:ring-2 focus:ring-blue-500 focus:ring-offset-1
                                    disabled:opacity-60 disabled:cursor-not-allowed">
                        <span wire:loading wire:target="invite_user({{ $row }})"
                            class="inline-flex items-center gap-1">
                            <svg class="size-3 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25"
                                    stroke-width="4" />
                                <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" />
                            </svg>
                            Sending…
                        </span>
                        <span wire:loading.remove wire:target="invite_user({{ $row }})">
                            Invite
                        </span>
                    </button>
                </td>

                <!-- Date -->
                <td class="px-3 py-2 whitespace-nowrap text-gray-600">
                    {{ $row->created_at }}
                </td>

                <td class="px-3 py-2">
                    <div class="flex items-center justify-center gap-1.5">

                        <!-- Edit -->
                        <a href="{{ route('user.edit', ['user' => $row]) }}" class="inline-flex items-center justify-center
            w-7 h-7 rounded-md border border-gray-200
            text-gray-500 hover:text-[#134169]
            hover:border-[#134169] hover:bg-slate-50
            transition">
                            <!-- Pencil icon (small & clean) -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l2.651 2.651M6.3 17.9l4.243-.707
                    8.486-8.486a1.5 1.5 0 000-2.121l-2.415-2.415
                    a1.5 1.5 0 00-2.121 0L5.657 12.95 6.3 17.9z" />
                            </svg>
                        </a>

                        <!-- Delete -->
                        <button @class([
                                "inline-flex items-center justify-center w-auto h-7 text-xs rounded-md border border-gray-200 text-gray-500 transition px-2",
                                'hover:text-red-600 hover:border-red-600 hover:bg-red-50' => $row->status == 1,
                                'hover:text-green-600 hover:border-green-600 hover:bg-green-50' => $row->status == 0,
                            ]) 
                            wire:click="toggleUserStatus({{ $row->id }}, {{ $row->status ? 0 : 1 }})">
                            <!-- Trash icon -->
                            {{ $row->status ? '❌ Disable' : '✅ Enable' }}
                        </button>

                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="11" class="px-3 py-6 text-center text-gray-400 text-sm">
                    No result
                </td>
            </tr>
            @endforelse
        </tbody>
    </x-table>
</div>