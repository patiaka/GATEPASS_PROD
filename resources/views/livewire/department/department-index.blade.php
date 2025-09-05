<div>
    <x-table title="List of department" :addbtn="false">

        <x-slot:addcreate>
            <x-button-add link="{{ route('department.create') }}" />
        </x-slot:addcreate>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full text-sm border-collapse">
                <thead class="text-[11px] uppercase text-white bg-[#0e3a61] sticky top-0 shadow z-10">
                    <tr>
                        <th scope="col" class="px-3 py-2 text-left">ID</th>
                        <th scope="col" class="px-3 py-2 text-left">Name</th>
                        <th scope="col" class="px-3 py-2 text-left">Date</th>
                        <th scope="col" class="px-3 py-2 text-left">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($this->rows as $row)
                        <tr wire:key='user-{{ $row->id }}' class="odd:bg-white even:bg-gray-50 hover:bg-blue-50">
                            <td class="px-3 py-2 whitespace-nowrap">{{ $row->id }}</td>

                            <td class="px-3 py-2">
                                <span class="truncate block max-w-[360px]" title="{{ $row->name }}">
                                    {{ $row->name }}
                                </span>
                            </td>

                            <td class="px-3 py-2 whitespace-nowrap text-gray-700">
                                {{ $row->created_at }}
                            </td>

                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <x-button-edit href="{{ route('department.edit', ['department' => $row]) }}" />
                                    <x-button-delete rowId="{{ $row->id }}" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-6 text-center text-gray-500">
                                No result
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </x-table>
</div>
