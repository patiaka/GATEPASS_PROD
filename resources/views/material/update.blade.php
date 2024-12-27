<x-app-layout>
    <div class="card">
        <div class="card-body">
            <x-form route="{{ route('material.update', ['material' => $material]) }}" type="update"
                url="{{ route('material.index') }}">

            </x-form>
        </div>
    </div>
</x-app-layout>