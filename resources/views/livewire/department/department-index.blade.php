<div>
    <x-table title="List of department">
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
                    {{--
                    <x-button-edit href="{{ route('department.edit', ['department' => $row]) }}" /> --}}
                    <x-button-delete rowId="{{ $row->id }}" />
                </td>
            </tr>
            @empty
            <h2 class="text-center">No result</h2>
            @endforelse
        </tbody>
    </x-table>
    <x-modal title="Formulaire de nouveau periode">
        <x-form route='save'>
            <x-input type="text" wire:model="form.name" name="form.name" label="Nom" place="le nom de la periode" />
        </x-form>
    </x-modal>
</div>