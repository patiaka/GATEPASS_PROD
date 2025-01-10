<x-app-layout>
    <x-datatable title="List of compagnie">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach ($rows as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->created_at }}</td>
                <td>
                    <x-button-edit href="{{ route('compagnie.edit', ['compagnie' => $row]) }}" />
                    <x-button-delete url="{{ url('compagnie/'.$row->id) }}" />
                </td>
            </tr>
            @endforeach
        </tbody>
    </x-datatable>

    <x-modal title="Form of new compagnie">
        <x-form route="{{ route('compagnie.store') }}">
            <x-input type="text" name="name" place="name of compagnie" />
        </x-form>
    </x-modal>
</x-app-layout>