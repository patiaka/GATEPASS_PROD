<div>
    <x-table title="List of users">

        <x-slot:filter>

            <div class="col-sm-6 col-md-3">
                <x-input-group type="text" wire:model.live="search" label="Search" />
            </div>
            <div class="col-sm-6 col-md-3">
                <x-select-group label="Department" wire:model.live='department'>
                    @foreach ($departments as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach

                </x-select-group>
            </div>
            <div class="col-sm-6 col-md-3">
                <x-select-group label="Role">
                    @foreach (App\Enum\RoleEnum::cases() as $row)
                    <option value="{{ $row }}">{{ $row }}</option>
                    @endforeach
                </x-select-group>
            </div>
        </x-slot:filter>
        <thead>
            <tr>
                <th>ID</th>
                <th>Department</th>
                <th>Email/nom</th>
                <th>role</th>
                {{-- <th>change MDP</th> --}}
                <th>Date creation</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($this->rows as $row)
            <tr wire:key='{{ $row->id }}'>
                <td>{{ $row->id }}</td>
                <td>{{ $row->department->name }}</td>
                <td>{{ $row->email }}<br>{{ $row->name }}</td>
                <td>{{ $row->role }}</td>
                {{-- <td>{{ $row->change_password ? 'OUI':'NON' }}</td> --}}

                <td>{{ $row->created_at }}</td>
                <td>
                    <x-button-edit href="{{ route('user.edit', ['user' => $row]) }}" />

                    <x-button-delete url="{{ url('user/'.$row->id) }}" />
                </td>
            </tr>
            @empty
            <h2 class="text-center">No result</h2>
            @endforelse
        </tbody>
    </x-table>
    <x-modal title="Form of new user">
        <x-form route="{{ route('user.store') }}">
            <div class="col-12">
                <x-input type="text" name="name" place="name" />
                <x-input type="email" name="email" place="email" />
                <x-select name="department_id" label="department">
                    @foreach ($departments as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </x-select>
                <x-select name="role" label="role">
                    @foreach (App\Enum\RoleEnum::cases() as $row)
                    <option value="{{ $row }}">{{ $row }}</option>
                    @endforeach
                </x-select>
            </div>
        </x-form>
    </x-modal>
</div>