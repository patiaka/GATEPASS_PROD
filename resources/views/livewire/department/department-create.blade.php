<div>
    <div class="card bg-white shadow-sm p-5">
        {{-- <div class="card-header">
            <h2 class="card-title text-center">Department create</h2>
        </div> --}}

        <!-- Header -->
        <div class="mb-6 border-b pb-4">
            <h2 class="text-2xl font-bold text-[#134169]">
                New department
            </h2>
            <p class="text-sm text-gray-500">
                Create a new department
            </p>
        </div>

        <x-form route='save' url="{{ route('department.index') }}">
            <x-input type="text" wire:model="form.name" name="form.name" label="Name" place="name of department" />
        </x-form>
    </div>
</div>