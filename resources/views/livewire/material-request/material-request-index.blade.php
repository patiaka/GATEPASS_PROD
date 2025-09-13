<div class="space-y-4">
    <x-table title="List of Material request" :addbtn="false">
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
                    <x-select label="Filter by Status" wire:model.live="status">
                        <option value="">All Statuses</option>
                        @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                        <option value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                    </x-select>
                </div>
            </div>
        </x-slot:filter>

        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
            <table class="min-w-[900px] w-full text-left">
                <thead class="bg-[#0e3a61] text-white sticky top-0 z-10">
                    <tr class="text-xs tracking-wider">
                        <th class="px-4 py-3 font-semibold">ID</th>
                        <th class="px-4 py-3 font-semibold">Reference</th>
                        <th class="px-4 py-3 font-semibold">Date</th>
                        <th class="px-4 py-3 font-semibold">Company</th>
                        <th class="px-4 py-3 font-semibold">Department</th>
                        <th class="px-4 py-3 font-semibold">Requestor</th>
                        <th class="px-4 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="bg-white">
                @forelse ($this->rows as $row)
                    <tr wire:key="row-{{ $row->id }}" class="border-b border-gray-100 odd:bg-white even:bg-gray-50 hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">#{{ $row->id }}</td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-200">
                                {{ $row->reference }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ \Illuminate\Support\Str::of($row->created_at) }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-200">
                                {{ $row->company }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-200">
                                {{ $row->user->department->name }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-800">
                            {{ $row->user->name }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <x-button-edit href="{{ route('material.edit', ['MaterialRequest' => $row]) }}" :row="$row" />
                                <x-button-show href="{{ route('material.show', ['MaterialRequest' => $row]) }}" :row="$row" />
                                <x-button-delete :row="$row" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-600">
                            No result
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </x-table>
</div>
