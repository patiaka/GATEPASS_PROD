<div>
    <x-table :title="__('Users Database')" :addbtn="false" :rows="$this->rows">

        <x-slot:addcreate>
            <div class="flex flex-col items-end gap-1">
                <div class="flex flex-wrap items-center gap-2">
                    {{-- Choisir un fichier --}}
                    <label class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-slate-600 text-sm cursor-pointer hover:bg-slate-50 transition"
                        wire:loading.class="opacity-60" wire:target="import_file">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0 4 4m-4-4L8 8M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                        </svg>
                        <span>
                            <span wire:loading.remove wire:target="import_file">{{ $import_file ? 'File ready' : 'Choose Excel' }}</span>
                            <span wire:loading wire:target="import_file">Loading…</span>
                        </span>
                        <input type="file" wire:model="import_file" accept=".xlsx,.xls" class="hidden">
                    </label>

                    @if ($import_file)
                        {{-- Département + rôle appliqués à toutes les lignes importées --}}
                        <select wire:model="import_department"
                            class="px-3 py-2 rounded-lg border border-gray-300 text-slate-600 text-sm focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
                            <option value="">Department…</option>
                            @foreach ($departments as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model="import_role"
                            class="px-3 py-2 rounded-lg border border-gray-300 text-slate-600 text-sm focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
                            <option value="">Role…</option>
                            @foreach (App\Enum\RoleEnum::cases() as $r)
                                <option value="{{ $r->value }}">{{ $r->value }}</option>
                            @endforeach
                        </select>

                        <button wire:click="import" wire:loading.attr="disabled" wire:target="import"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition disabled:opacity-60">
                            <span wire:loading.remove wire:target="import">Import</span>
                            <span wire:loading wire:target="import" class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25" stroke-width="4" />
                                    <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                                </svg>
                                Importing…
                            </span>
                        </button>
                    @endif

                    <button wire:click="downloadTemplate"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-300 text-slate-600 text-sm hover:bg-slate-50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                        </svg>
                        Template
                    </button>

                    <x-button-add link="{{ route('user.create') }}" />
                </div>
                @if ($import_file)
                    <span class="text-slate-400 text-xs">Pick a department &amp; role, then Import — applied to all rows.</span>
                @endif
                @error('import_file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                @error('import_department') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                @error('import_role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </x-slot:addcreate>

        <x-slot:filter>
            <div class="flex flex-wrap gap-3 items-end">
                <div class="w-full sm:w-44">
                    <x-select label="Department" name="department" wire:model.live="department">
                        <option value="">All Departments</option>
                        @foreach ($departments as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div class="w-full sm:w-36">
                    <x-select label="Role" wire:model.live="role">
                        <option value="">All Roles</option>
                        @foreach (App\Enum\RoleEnum::cases() as $row)
                        <option value="{{ $row->value }}">{{ $row->value }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div class="w-full sm:w-36">
                    <x-select label="Status" wire:model.live="status">
                        <option value="">All statuses</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </x-select>
                </div>
            </div>
        </x-slot:filter>

        <!-- THEAD -->
        <thead class="sticky top-0 z-20">
            <tr class="uppercase tracking-wide text-[12px] bg-slate-100 text-slate-700 border-b">
                <th class="px-3 py-2 text-left font-semibold">{{ __('Department') }}</th>
                <th class="px-3 py-2 text-left font-semibold">{{ __('Email / Name') }}</th>
                <th class="px-3 py-2 text-left font-semibold">{{ __('Position') }}</th>
                <th class="px-3 py-2 text-left font-semibold">{{ __('Role') }}</th>
                <th class="px-3 py-2 text-left font-semibold">{{ __('Other roles') }}</th>
                <th class="px-3 py-2 text-left font-semibold">{{ __('Status') }}</th>
                <th class="px-3 py-2 text-left font-semibold">{{ __('Change PWD') }}</th>
                <th class="px-3 py-2 text-left font-semibold">{{ __('Invite') }}</th>
                <th class="px-3 py-2 text-left font-semibold">{{ __('Date') }}</th>
                <th class="px-3 py-2 text-center font-semibold">{{ __('Action') }}</th>
            </tr>
        </thead>

        <!-- TBODY -->
        <tbody class="divide-y divide-gray-100">
            @forelse ($this->rows as $row)
            <tr wire:key="user-{{ $row->id }}" class="odd:bg-white even:bg-gray-50/40 hover:bg-slate-50 transition">

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

                <!-- Roles (primaire) -->
                <td class="px-3 py-2">
                    @php $userRoles = $row->currentRoles(); @endphp
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold
                                    bg-blue-50 text-blue-700 border border-blue-200">
                        {{ $userRoles[0] ?? '—' }}
                    </span>
                </td>

                <!-- Roles (secondaires) -->
                <td class="px-3 py-2">
                    <div class="flex flex-wrap gap-1">
                        @foreach (array_slice($userRoles, 1) as $extraRole)
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold
                                        bg-purple-50 text-purple-700 border border-purple-200">
                                {{ $extraRole }}
                            </span>
                        @endforeach
                    </div>
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
                        <a href="{{ route('user.edit', ['user' => $row]) }}" title="Edit user"
                            class="inline-flex items-center justify-center w-7 h-7 rounded-md border border-gray-200
                                   text-gray-500 hover:text-[#134169] hover:border-[#134169] hover:bg-slate-50 transition">
                            <!-- Pencil icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l2.651 2.651M6.3 17.9l4.243-.707
                    8.486-8.486a1.5 1.5 0 000-2.121l-2.415-2.415
                    a1.5 1.5 0 00-2.121 0L5.657 12.95 6.3 17.9z" />
                            </svg>
                        </a>

                        <!-- Enable / Disable (icône seule) -->
                        <button wire:click="toggleUserStatus({{ $row->id }}, {{ $row->status ? 0 : 1 }})"
                            wire:target="toggleUserStatus({{ $row->id }}, {{ $row->status ? 0 : 1 }})"
                            wire:loading.attr="disabled"
                            title="{{ $row->status ? 'Disable user' : 'Enable user' }}"
                            @class([
                                'inline-flex items-center justify-center w-7 h-7 rounded-md border border-gray-200 text-gray-500 transition disabled:opacity-50',
                                'hover:text-rose-600 hover:border-rose-600 hover:bg-rose-50' => $row->status == 1,
                                'hover:text-emerald-600 hover:border-emerald-600 hover:bg-emerald-50' => $row->status == 0,
                            ])>
                            {{-- Spinner pendant le traitement --}}
                            <svg wire:loading wire:target="toggleUserStatus({{ $row->id }}, {{ $row->status ? 0 : 1 }})"
                                class="w-3.5 h-3.5 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25" stroke-width="4" />
                                <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" />
                            </svg>

                            <span wire:loading.remove wire:target="toggleUserStatus({{ $row->id }}, {{ $row->status ? 0 : 1 }})"
                                class="inline-flex">
                                @if ($row->status)
                                    {{-- Ban / no-symbol : désactiver --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18.364 5.636 5.636 18.364M12 21a9 9 0 1 1 0-18 9 9 0 0 1 0 18Z" />
                                    </svg>
                                @else
                                    {{-- Check-circle : réactiver --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9 12.75 2.25 2.25L15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                @endif
                            </span>
                        </button>

                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="10" class="px-4 py-12 text-center">
                    <div class="flex flex-col items-center gap-3 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                        </svg>
                        <p class="text-sm">{{ __('No user found') }}</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </x-table>
</div>