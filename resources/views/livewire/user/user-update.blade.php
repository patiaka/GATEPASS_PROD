<div>
    <div class="card bg-white shadow-sm p-5">
        <div class="card-header">
            <h2 class="card-title text-center">User update</h2>
        </div>
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
                        <label><input type="radio" wire:model="form.role" name="form.resident" value="{{ $row }}"
                                class="form-radio">
                            {{ $row }}</label>
                        @endforeach

                    </div>
                    @error('form.role') <small class="text-red-500 text-sm">{{ $message }}</small> @enderror
                </div>
            </div>
        </x-form>
    </div>
</div>
</div>