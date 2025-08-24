<div>
    <div class="card bg-white shadow-sm p-5">
        <div class="card-header">
            <h2 class="card-title text-center">Department create</h2>
        </div>
        <x-form route='save' url="{{ route('department.index') }}">
            <x-input type="text" wire:model="form.name" name="form.name" label="Name" place="name of department" />
        </x-form>
    </div>
</div>