<div>
    <x-table title="Liste des utilisateurs" :addbtn="false">
        <x-slot:addcreate>
            <a href="{{ route('user.create') }}" role="button" class="btn btn-primary">
                <i class='me-1 bx bx-plus-circle'></i> Nouveau
            </a>
        </x-slot:addcreate>
        <x-slot:filter>

                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating">
                        <label class="focus-label">Search</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating">
                            <option>Select Company</option>
                            <option>Global Technologies</option>
                            <option>Delta Infotech</option>
                        </select>
                        <label class="focus-label">Company</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating">
                            @foreach (App\Enum\RoleEnum::cases() as $row)
                            <option value="{{ $row }}">{{ $row }}</option>
                            @endforeach
                        </select>
                        <label class="focus-label">Role</label>
                    </div>
                </div>


        </x-slot:filter>
        <thead>
            <tr>
                <th>ID</th>
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
            <h2 class="text-center">Aucun resultat</h2>
            @endforelse
        </tbody>
    </x-table>
</div>
