<div>
    <x-table title="List of users">

        <x-slot:filter>
            <div class="col-sm-6 col-md-3">
                <x-select label="Department" wire:model.live='department'>
                    @foreach ($departments as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div class="col-sm-6 col-md-3">
                <x-select label="compagnie" wire:model.live='compagnie'>
                    @foreach ($compagnies as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div class="col-sm-6 col-md-3">
                <x-select label="Role" wire:model.live='role'>
                    @foreach (App\Enum\RoleEnum::cases() as $row)
                    <option value="{{ $row }}">{{ $row }}</option>
                    @endforeach
                </x-select>
            </div>
        </x-slot:filter>
        <thead>
            <tr>
                <th>ID</th>
                <th>Department</th>
                <th>Compagny</th>
                <th>Email/nom</th>
                <th>Position</th>
                <th>role</th>
                <th>status</th>
                <th>change MDP</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($this->rows as $row)
            <tr wire:key='user-{{ $row->id }}'>
                <td>{{ $row->id }}</td>
                <td>{{ $row->department->name }}</td>
                <td>{{ $row->compagnie->name }}</td>
                <td>{{ $row->email }}<br>{{ $row->name }}</td>
                <td>{{ $row->poste }}</td>
                <td>{{ $row->role }}</td>
                <td>

                    <span @class(['btn badge rounded-pill btn-sm' ,'bg-primary'=> $row->status == 1,
                        'bg-danger' => $row->status == 0,
                        ])>
                        {{ $row->status ? 'Active':'Inactive' }}
                    </span>
                </td>
                <td>{{ $row->change_password ? 'OUI':'NON' }}</td>

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
                <x-input type="text" name="poste" place="poste" />
                <x-select name="department_id" label="department">
                    @foreach ($departments as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </x-select>
                <x-select name="compagnie_id" label="compagnie">
                    @foreach ($compagnies as $row)
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
<script>
    $(".select2").each(function () {
            var current = $(this);
            current.wrap('<div class="position-relative"></div>').select2({
                placeholder: "Selectionner",
                dropdownParent: current.parent(),
            });
            // Get the Livewire property name from the wire:model attribute
            var propertyName = current.attr('wire:model.live');
            // Listen for change event and update Livewire property
            current.on('change', function (e) {
            // Add opacity to table
            $('.table-responsive').addClass('opacity-50');

            @this.set(propertyName, $(this).val()).then(() => {
                // Remove opacity after Livewire updates
                $('.table-responsive').removeClass('opacity-50');
            });
            });
        });
</script>