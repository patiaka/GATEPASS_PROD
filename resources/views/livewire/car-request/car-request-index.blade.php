<div class="space-y-4">
    <x-table title="All Resident & Vehicle Offsite request" :addbtn="false">
        <x-slot:addcreate>
            <a href="{{ route('car.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
              bg-[#0e3a61] text-white text-sm font-medium
              hover:bg-[#0c3253] shadow-sm transition
              focus:outline-none focus:ring-2 focus:ring-white/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New Vehicle Offsite
            </a>
        </x-slot:addcreate>


        <x-slot:filter>
            <div class="flex flex-wrap items-end gap-3 w-full">
                {{-- Left section: filters + bulk actions --}}
                <div class="flex flex-wrap items-end gap-3 flex-grow">

                    @if (!empty($selectedRows))
                        <div class="flex gap-2">
                            <button
                                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 disabled:opacity-60"
                                wire:click="bulkAction('reject','car')" wire:loading.attr="disabled"
                                wire:target="bulkAction">
                                <span wire:loading.remove wire:target="bulkAction">Reject</span>
                                <span wire:loading wire:target="bulkAction">
                                    <i class="bx bx-loader-alt fa-spin"></i> Processing...
                                </span>
                            </button>

                            <button
                                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-300 disabled:opacity-60"
                                wire:click="bulkAction('approve','car')" wire:loading.attr="disabled"
                                wire:target="bulkAction">
                                <span wire:loading.remove wire:target="bulkAction">Approve</span>
                                <span wire:loading wire:target="bulkAction">
                                    <i class="bx bx-loader-alt fa-spin"></i> Processing...
                                </span>
                            </button>
                        </div>
                    @endif

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
            </div>
        </x-slot:filter>

        <!-- THEAD -->
        <thead class="sticky top-0 z-20">
            <tr class="uppercase tracking-wide text-[12px] bg-slate-100 text-slate-700 border-b">
                <th class="px-4 py-2 font-semibold">ID</th>
                <th class="px-4 py-2 font-semibold">Reference</th>
                <th class="px-4 py-2 font-semibold">Date</th>
                <th class="px-4 py-2 font-semibold">Company</th>
                <th class="px-4 py-2 font-semibold">Department</th>
                <th class="px-4 py-2 font-semibold">Requestor</th>
                <th class="px-4 py-2 font-semibold">Status</th>
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
                        <a href="{{ route('car.show', ['CarRequest' => $row]) }}"
                            class="inline-flex items-center gap-1.5 rounded-md bg-indigo-50 px-2 py-1
                            text-[11px] font-semibold text-indigo-700
                            ring-1 ring-inset ring-indigo-200
                            hover:bg-indigo-100 hover:text-indigo-800 transition">
                            {{ $row->reference }}
                        </a>
                    </td>

                    <td class="px-4 py-2 text-gray-700">
                        {{ \Illuminate\Support\Str::of($row->created_at) }}
                    </td>

                    <td class="px-4 py-2">
                        <span
                            class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1
                            text-[11px] font-medium text-slate-700
                            ring-1 ring-inset ring-slate-200">
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

                    <td class="px-4 py-2">
                        <span 
                            @class([
                                "inline-flex items-center rounded-md px-2 py-1 text-[10px] item-center justify-center
                                font-medium text-green-700 ring-1 ring-inset ring-green-700 w-16",
                                'text-red-700 ring-red-700' => $row->isRejected(),
                                'bg-red-100 text-red-700 ring-red-700' => $row->isExpired(),
                                'text-orange-600 ring-orange-600' => $row->isPending(),
                                'text-yellow-700 ring-yellow-700' => $row->isProgress(),
                                'text-green-700 ring-green-700' => $row->isApproved(),
                            ])>
                            {{ $row->status }}
                        </span>
                    </td>

                    <!-- ACTIONS -->
                    <td class="px-4 py-2">
                        <div class="flex items-center justify-center gap-1.5">
                            <!-- on garde tes composants, mais on les rend plus compacts -->
                            <x-button-edit href="{{ route('car.edit', ['CarRequest' => $row]) }}" :row="$row" />

                            <x-button-show href="{{ route('car.show', ['CarRequest' => $row]) }}" :row="$row" />

                            <x-button-delete :row="$row" :rowId="$row->id" />
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

    </x-table>
</div>
