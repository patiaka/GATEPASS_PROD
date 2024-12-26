<x-app-layout>
    <div class="card">
        <div class="card-body">
            <x-form route="{{ route('department.update', ['department' => $department]) }}" type="update"
                url="{{ route('department.index') }}">
                <x-input type="text" name="name" place="name of departement" :value="$department->name" />
            </x-form>
        </div>
    </div>
</x-app-layout>