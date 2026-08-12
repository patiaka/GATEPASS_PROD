<div>
    <x-table :title="__('Department Database')" :addbtn="false" :rows="$this->rows">

        <x-slot:addcreate>
            <x-button-add link="{{ route('department.create') }}" />
        </x-slot:addcreate>

        <!-- THEAD -->
        <thead class="sticky top-0 z-20">
            <tr class="uppercase tracking-wide text-[12px] bg-slate-100 text-slate-700 border-b">
                <th scope="col" class="px-4 py-2 text-left font-semibold">{{ __('Name') }}</th>
                <th scope="col" class="px-4 py-2 text-left font-semibold">{{ __('Director') }}</th>
                <th scope="col" class="px-4 py-2 text-left font-semibold">{{ __('Date') }}</th>
                <th scope="col" class="px-4 py-2 text-center font-semibold">{{ __('Action') }}</th>
            </tr>
        </thead>

        <!-- TBODY -->
        <tbody class="divide-y divide-gray-100">
            @forelse ($this->rows as $row)
                <tr wire:key="dept-{{ $row->id }}"
                    class="odd:bg-white even:bg-gray-50/40 hover:bg-slate-50 transition">

                    <td class="px-4 py-2 text-gray-700">
                        <span class="truncate block max-w-[360px]" title="{{ $row->name }}">
                            {{ $row->name }}
                        </span>
                    </td>

                    <td class="px-4 py-2 text-gray-600 whitespace-nowrap">
                        <span
                            @class([ 'inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold border'
                            , 'bg-emerald-50 text-emerald-700 border-emerald-200'=> $row->director_id !== null,
                            // 'bg-rose-50 text-rose-700 border-rose-200' => $row->director_id === null,
                            ])>
                            {{ $row->director ? $row->director->name : __('No Director') }}
                        </span>
                    </td>

                    <td class="px-4 py-2 text-gray-600 whitespace-nowrap">
                        {{ $row->created_at }}
                    </td>

                    <!-- ACTIONS -->
                    <td class="px-4 py-2">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Edit -->
                            <a href="{{ route('department.edit', ['department' => $row]) }}"
                                class="p-1.5 rounded-md border border-gray-200 text-gray-600 
                                        hover:text-[#134169] hover:border-[#134169] 
                                        hover:bg-slate-50 transition">
                                <!-- Pencil icon (clean & pro) -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l2.651 2.651M6.3 17.9l4.243-.707 8.486-8.486a1.5 1.5 0 000-2.121l-2.415-2.415a1.5 1.5 0 00-2.121 0l-8.486 8.486L6.3 17.9z" />
                                </svg>
                            </a>

                            <!-- Delete -->
                            <button
                                wire:click="delete({{ $row->id }})"
                                class="p-1.5 rounded-md border border-gray-200 text-gray-600
                                        hover:text-red-600 hover:border-red-600 
                                        hover:bg-red-50 transition">
                                <!-- Trash icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-3h4a1 1 0 011 1v1H9V5a1 1 0 011-1z" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center gap-3 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                            </svg>
                            <p class="text-sm">{{ __('No department found') }}</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>

    </x-table>
</div>
