<div>
    <x-table title="Material Gate Pass Approval" :addbtn="false" :rows="$this->rows">
        {{-- <x-slot:filter>
            <div class="flex flex-wrap gap-4 items-end">
                @if(!empty($selectedRows))
                <div class="flex gap-2">
                    <button class="btn btn-danger" wire:click="bulkAction('reject','material')"
                        wire:loading.attr="disabled" wire:target="bulkAction">
                        <span wire:loading.remove wire:target="bulkAction">Reject</span>
                        <span wire:loading wire:target="bulkAction">
                            <i class="bx bx-loader-alt fa-spin"></i> Processing...
                        </span>
                    </button>

                    <button class="btn btn-success" wire:click="bulkAction('approve','material')"
                        wire:loading.attr="disabled" wire:target="bulkAction">
                        <span wire:loading.remove wire:target="bulkAction">Approve</span>
                        <span wire:loading wire:target="bulkAction">
                            <i class="bx bx-loader-alt fa-spin"></i> Processing...
                        </span>
                    </button>
                </div>
                @endif

                @if (Auth::user()->isGm() || Auth::user()->isAdmin())
                <div class="w-full sm:w-48">
                    <x-select label="Filter by Department" name="department" wire:model.live="department">
                        <option value="">All Departments</option>
                        @foreach ($departments as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                @endif

                <div class="w-full sm:w-48">
                    <x-select label="Filter by Status" wire:model.live="status">
                        <option value="">All Statuses</option>
                        @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                        <option value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                    </x-select>
                </div>
            </div>
        </x-slot:filter> --}}

        <thead class="sticky top-0 z-20">
            <tr class="uppercase tracking-wide text-[12px] bg-slate-100 text-slate-700 border-b">
                {{-- <th class="px-4 py-3">
                    <input type="checkbox" wire:click="selectAll" wire:model="selectedRows" id="select-all">
                </th> --}}
                {{-- <th class="px-4 py-2 text-left font-semibold">#</th> --}}
                <th class="px-4 py-2 text-left font-semibold">reference</th>
                <th class="px-4 py-2 text-left font-semibold">Date</th>
                <th class="px-4 py-2 text-left font-semibold">Company</th>
                <th class="px-4 py-2 text-left font-semibold">Department</th>
                {{-- @if (Auth::user()->isGm() || Auth::user()->isHod())
                <th class="px-4 py-2 text-left font-semibold">Requestor</th>
                @endif --}}
                <th class="px-4 py-2 text-left font-semibold">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100 bg-white">
            @forelse ($this->rows as $row)
                <tr wire:key="row-{{ $row->id }}">
                    {{-- <td class="px-4 py-2">
                        <input type="checkbox" wire:model.live="selectedRows" value="{{ $row->id }}">
                    </td> --}}
                    {{-- <td class="px-4 py-2">{{ $index + 1 }}</td> --}}
                    <td class="px-4 py-2">
                        <a href="{{ route('material.show', ['MaterialRequest' => $row]) }}"
                            class="inline-flex items-center gap-1.5 rounded-md bg-indigo-50 px-2 py-1
                            text-[11px] font-semibold text-indigo-700
                            ring-1 ring-inset ring-indigo-200
                            hover:bg-indigo-100 hover:text-indigo-800 transition">
                            {{ $row->reference }}
                        </a>
                    </td>

                    <td class="px-4 py-2">{{ $row->created_at }}</td>
                    <td class="px-4 py-2">{{ $row->company }}</td>
                    <td class="px-4 py-2">{{ $row->user->department->name }}</td>
                    <td class="px-4 py-3 flex items-center gap-2">
                        <x-button-edit href="{{ route('material.edit', ['MaterialRequest' => $row]) }}" :row="$row" />
                        <x-button-show href="{{ route('material.show', ['MaterialRequest' => $row]) }}" :row="$row" />
                        <x-button-delete :row="$row" />

                        @if (Auth::user()->isGm() || Auth::user()->isHod())
                            <x-form-request wire:key="request-{{ $row->id }}" :model="$row" type="material" />
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4">You have no pending approval requests</td>
                </tr>
            @endforelse
        </tbody>

    </x-table>
</div>