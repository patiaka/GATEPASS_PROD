<x-form action='save' type="update" route="department.index">
    <div>
        <x-input type="text" wire:model="form.name" name="form.name" label="Nom" place="name of department" />
    </div>
    
    <!-- Director -->
    <div class="w-1/2">
        <x-select label="Director" name="director_id" wire:model='form.director_id' class="bg-amber-600">
            @foreach ($users as $row)
                <option value="{{ $row->id }}" {{ $row->id === $form->director_id ? 'selected' : '' }}>
                    {{ $row->full_name }}
                </option>
            @endforeach
        </x-select>
        @error('form.director_id')
            <small class="text-red-500 text-xs">{{ $message }}</small>
        @enderror
    </div>
</x-form>