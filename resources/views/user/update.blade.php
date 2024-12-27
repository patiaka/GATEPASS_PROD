<x-app-layout>
    <div class="card">
        <div class="card-body">
            <x-form route="{{ route('user.update', ['user' => $user]) }}" type="update" url="{{ route('user.index') }}">
                <div class="col-md-12">
                    <x-input type="text" name="name" place="name" :value="$user->name" />
                    <x-input type="email" name="email" place="email" :value="$user->email" />
                    <x-select name="department_id" label="Department">
                        @foreach ($department as $row)
                        <option @selected($row->id === $user->department_id) value="{{ $row->id }}">{{ $row->name }}
                        </option>
                        @endforeach
                    </x-select>

                    <x-select name="role" label="Role">
                        @foreach (App\Enum\RoleEnum::cases() as $row)
                        <option @selected($row===$user->role) value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                    </x-select>
                </div>
            </x-form>
        </div>
    </div>
</x-app-layout>