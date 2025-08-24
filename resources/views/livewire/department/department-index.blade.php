<div>
    <x-table title="List of department" :addbtn="false">

        <x-slot:addcreate>
            <x-button-add link="{{ route('department.create') }}" />
        </x-slot:addcreate>
        <thead class="text-xs uppercase bg-gray-200 text-gray-600">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">name</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Action</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($this->rows as $row)
            <tr wire:key='user-{{ $row->id }}' class="border-b hover:bg-gray-100">
                <td class="px-4 py-3">{{ $row->id }}</td>
                <td class="px-4 py-3">{{ $row->name }}</td>
                <td class="px-4 py-3">{{ $row->created_at }}</td>
                <td class="px-4 py-3">
                    <x-button-edit href="{{ route('department.edit', ['department' => $row]) }}" />
                    <x-button-delete rowId="{{ $row->id }}" />
                </td>
            </tr>
            @empty
            <h2 class="text-center">No result</h2>
            @endforelse
        </tbody>
    </x-table>

</div>