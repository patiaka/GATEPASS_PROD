<div class="space-y-4">
    <x-table title="All Material Offsite request" :addbtn="false">
        <x-slot:addcreate>
            <a href="{{ route('material.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
              bg-[#0e3a61] text-white text-sm font-medium
              hover:bg-[#0c3253] shadow-sm transition
              focus:outline-none focus:ring-2 focus:ring-white/30">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>

                New Material Request
            </a>
        </x-slot:addcreate>

        <x-slot:filter>
            <div class="flex flex-wrap items-end gap-3">
                @if (Auth::user()->isGm() || Auth::user()->isAdmin())
                    <div class="w-full sm:w-56">
                        <x-select label="Filter by Department" name="department" wire:model.live="department">
                            <option value="">All Departments</option>
                            @foreach ($departments as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </x-select>
                    </div>
                @endif

                <div class="w-full sm:w-56">
                    <x-select label="Filter by Status" wire:model.live="by_status">
                        <option value="">All Statuses</option>
                        @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                            <option value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                    </x-select>
                </div>
            </div>
        </x-slot:filter>

        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm bg-white">
            <table class="min-w-[900px] w-full text-left text-[13px]">

                <!-- THEAD -->
                <thead class="sticky top-0 z-20">
                    <tr class="uppercase tracking-wide text-[12px] bg-slate-100 text-slate-700 border-b">
                        <th class="px-4 py-2 font-semibold">ID</th>
                        <th class="px-4 py-2 font-semibold">Reference</th>
                        <th class="px-4 py-2 font-semibold">Date</th>
                        <th class="px-4 py-2 font-semibold">Company</th>
                        <th class="px-4 py-2 font-semibold">Department</th>
                        <th class="px-4 py-2 font-semibold">Requestor</th>
                        <th class="px-4 py-2 font-semibold text-center">Actions</th>
                    </tr>
                </thead>

                <!-- TBODY -->
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($this->rows as $row)
                        <tr wire:key="row-{{ $row->id }}"
                            class="odd:bg-white even:bg-gray-50/40 hover:bg-slate-50 transition">

                            <td class="px-4 py-2 font-medium text-gray-800">
                                #{{ $row->id }}
                            </td>

                            <td class="px-4 py-2">
                                <span
                                    class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1
                                   text-[11px] font-semibold text-indigo-700
                                   ring-1 ring-inset ring-indigo-200">
                                    <a href="{{ route('material.show', ['MaterialRequest' => $row]) }}">{{ $row->reference }}</a>
                                </span>
                            </td>

                            <td class="px-4 py-2 text-gray-700">
                                {{ \Illuminate\Support\Str::of($row->created_at) }}
                            </td>

                            <td class="px-4 py-2">
                                <span
                                    class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1
                                   text-[11px] font-medium text-amber-700
                                   ring-1 ring-inset ring-amber-200">
                                    {{ $row->company }}
                                </span>
                            </td>

                            <td class="px-4 py-2">
                                <span
                                    class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1
                                   text-[11px] font-medium text-slate-700
                                   ring-1 ring-inset ring-slate-200">
                                    {{ $row->user->department->name }}
                                </span>
                            </td>

                            <td class="px-4 py-2 text-gray-800">
                                {{ $row->user->name }}
                            </td>

                            <!-- ACTIONS -->
                            <td class="px-4 py-2">
                                <div class="flex items-center justify-center gap-1.5">
                                    <x-button-edit class="scale-90"
                                        href="{{ route('material.edit', ['MaterialRequest' => $row]) }}"
                                        :row="$row" />

                                    <x-button-show class="scale-90"
                                        href="{{ route('material.show', ['MaterialRequest' => $row]) }}"
                                        :row="$row" />

                                    <x-button-delete class="scale-90" :row="$row" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-400">
                                No result
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </x-table>
</div>
