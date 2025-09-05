<div>
    <div class="card bg-white shadow-sm rounded-xl border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-[#0e3a61] tracking-wide mb-4">
            Update User
        </h2>

        <x-form route="save" type="update" url="{{ route('user.index') }}">
            {{-- Champs en colonne (un par ligne) --}}
            <div class="flex flex-col space-y-3">
                {{-- Name --}}
                <div>
                    <x-input id="name" type="text" name="name" place="name" wire:model="form.name" />
                    @error('form.name') <small class="text-red-600 text-xs">{{ $message }}</small> @enderror
                </div>

                {{-- Email --}}
                <div>
                    
                    <x-input id="email" type="email" name="email" place="email" wire:model="form.email" />
                    @error('form.email') <small class="text-red-600 text-xs">{{ $message }}</small> @enderror
                </div>

                {{-- Position --}}
                <div>
                    
                    <x-input id="poste" type="text" name="poste" place="poste" wire:model="form.poste" />
                    @error('form.poste') <small class="text-red-600 text-xs">{{ $message }}</small> @enderror
                </div>

                {{-- Department (garde le label du composant) --}}
                <div>
                    <x-select label="Department" name="department" wire:model="form.department_id">
                        @foreach ($departments as $row)
                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    </x-select>
                    @error('form.department_id') <small class="text-red-600 text-xs">{{ $message }}</small> @enderror
                </div>
            </div>

            {{-- Role (radios en colonne) --}}
            <fieldset class="mt-4">
                <legend class="block text-xs font-medium text-gray-700 mb-2">Role</legend>
                <div class="flex flex-col gap-2">
                    @foreach (App\Enum\RoleEnum::cases() as $row)
                        <label class="flex items-center gap-2 rounded-md border border-gray-200 bg-white px-3 py-2 hover:bg-gray-50">
                            <input type="radio" wire:model="form.role" name="form.role" value="{{ $row }}" class="form-radio" />
                            <span class="text-sm text-gray-800">{{ $row }}</span>
                        </label>
                    @endforeach
                </div>
                @error('form.role') <small class="text-red-600 text-xs">{{ $message }}</small> @enderror
            </fieldset>

            {{-- Delegated role (vertical aussi) --}}
            @if (Auth::user()->isGm() || Auth::user()->isHod() || Auth::user()->isAdmin())
                <fieldset class="mt-4">
                    <legend class="block text-xs font-medium text-gray-700 mb-2">Delegated role</legend>
                    <div class="flex flex-col gap-2">
                        @foreach (App\Enum\RoleEnum::cases() as $row)
                            @continue(in_array($row->value, ['Administrator', 'Security', 'User']))
                            <label class="flex items-center gap-2 rounded-md border border-gray-200 bg-white px-3 py-2 hover:bg-gray-50">
                                <input type="radio" wire:model="form.delegated_role" id="{{ $row->value }}" name="form.delegated_role" value="{{ $row->value }}" class="form-radio" />
                                <span class="text-sm text-gray-800">{{ $row->value }}</span>
                            </label>
                        @endforeach

                        <label class="flex items-center gap-2 rounded-md border border-rose-200 bg-rose-50 px-3 py-2 hover:bg-rose-100">
                            <input type="radio" wire:model="form.delegated_role" id="revoke-role" name="form.delegated_role" value="" class="form-radio" />
                            <span class="text-sm text-rose-700">Revoke role</span>
                        </label>
                    </div>
                    @error('form.delegated_role') <small class="text-red-600 text-xs">{{ $message }}</small> @enderror
                </fieldset>
            @endif
        </x-form>
    </div>
</div>
