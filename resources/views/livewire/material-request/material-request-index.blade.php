<div class="space-y-4">
    <x-table :title="__('All Material Offsite request')" :addbtn="false" :rows="$this->rows">
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

                {{ __('New Material Request') }}
            </a>
        </x-slot:addcreate>

        <x-slot:filter>
            <div class="flex flex-wrap items-end gap-3">
                @if (Auth::user()->isGm() || Auth::user()->isAdmin())
                    <div class="w-full sm:w-56">
                        <x-select :label="__('Filter by Department')" name="department" wire:model.live="department">
                            <option value="">{{ __('All Departments') }}</option>
                            @foreach ($departments as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </x-select>
                    </div>
                @endif

                <div class="w-full sm:w-56">
                    <x-select :label="__('Filter by Status')" wire:model.live="by_status">
                        <option value="">{{ __('All Statuses') }}</option>
                        @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                            <option value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                    </x-select>
                </div>

                {{-- Filtre période --}}
                <div class="inline-flex items-center rounded-lg border border-gray-300 bg-gray-50 p-0.5 self-end">
                    @foreach (['all' => 'All', 'today' => 'Today', 'week' => 'Week', 'month' => 'Month'] as $key => $label)
                        <button type="button" wire:click="setPeriod('{{ $key }}')" @class([
                            'px-3 py-1.5 rounded-md text-xs font-medium transition whitespace-nowrap',
                            'bg-[#134169] text-white shadow-sm' => $period === $key,
                            'text-slate-600 hover:text-slate-900' => $period !== $key,
                        ])>{{ __($label) }}</button>
                    @endforeach
                </div>
            </div>
        </x-slot:filter>

        <!-- THEAD -->
        <thead class="sticky top-0 z-20">
            <tr class="uppercase tracking-wide text-[12px] bg-slate-100 text-slate-700 border-b">
                {{-- <th class="px-4 py-2 font-semibold">#</th> --}}
                <th class="px-4 py-2 font-semibold">{{ __('Reference') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Date') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Company') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Department') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Requestor') }}</th>
                <th class="px-4 py-2 font-semibold">{{ __('Status') }}</th>
                <th class="px-4 py-2 font-semibold text-center">{{ __('Actions') }}</th>
            </tr>
        </thead>

        <!-- TBODY -->
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse ($this->rows as $row)
                <tr wire:key="row-{{ $row->id }}"
                    class="odd:bg-white even:bg-gray-50/40 hover:bg-slate-50 transition">

                    {{-- <td class="px-4 py-2 font-medium text-gray-800">
                        {{ $index + 1 }}
                    </td> --}}

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

                    <td class="px-4 py-2 text-semibold">
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
                            @can('update-request', $row)
                                <x-button-edit href="{{ route('material.edit', ['MaterialRequest' => $row]) }}" :row="$row" />
                            @endcan

                            <x-button-show href="{{ route('material.show', ['MaterialRequest' => $row]) }}" :row="$row" />

                            <x-button-delete class="scale-90" :row="$row" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center gap-3 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7 12 3 4 7m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <p class="text-sm">{{ __('No material request found') }}</p>
                            <a wire:navigate href="{{ route('material.create') }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#134169] text-white text-xs font-medium hover:bg-[#0f3557] transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('New request') }}
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>

    </x-table>
</div>
