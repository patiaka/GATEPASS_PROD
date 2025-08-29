<div>
    <div class="card bg-white shadow-sm p-5">

        <x-form route='save' type="update" url="{{ route('user.index') }}">
            <div class="">
                <x-input type="text" name="name" place="name" wire:model='form.name' />
                <x-input type="email" name="email" place="email" wire:model='form.email' />
                <x-input type="text" name="poste" place="poste" wire:model='form.poste' />
                <x-select label="Department" name="department" wire:model='form.department_id'>
                    @foreach ($departments as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </x-select>


                <div>
                    <label class="block text-sm font-medium text-gray-700 my-3">Role</label>
                    <div class="flex gap-4">
                        @foreach (App\Enum\RoleEnum::cases() as $row)
                        <label><input type="radio" wire:model="form.role" name="form.role" value="{{ $row }}"
                                class="form-radio">
                            {{ $row }}</label>
                        @endforeach

                    </div>
                    @error('form.role') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>
                @if (Auth::user()->isGm() || Auth::user()->isHod() || Auth::user()->isAdmin())
                <div>
                    <label class="block text-sm font-medium text-gray-700 my-3">Delegated role</label>
                    <div class="flex gap-4">
                        @foreach (App\Enum\RoleEnum::cases() as $row)
                        @continue(in_array($row->value, ["Administrator", "Security",'User']))
                        <label>
                            <input type="radio" wire:model="form.delegated_role" id="{{ $row->value }}"
                                name="form.delegated_role" value="{{ $row->value }}" class="form-radio">
                            {{ $row->value }}
                        </label>
                        @endforeach

                        <label>
                            <input type="radio" wire:model="form.delegated_role" id="revoke-role"
                                name="form.delegated_role" value="" class="form-radio">
                            Revoke role
                        </label>
                    </div>

                    @error('form.delegated_role') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>
                @endif
            </div>
        </x-form>
    </div>
</div>
</div>
