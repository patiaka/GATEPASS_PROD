<x-app-layout>
    <div class="card">
        <div class="card-body">
            <x-form route="{{ route('compagnie.update', ['compagnie' => $compagnie]) }}" type="update"
                url="{{ route('compagnie.index') }}">
                <x-input type="text" name="name" place="name of Compagnie" :value="$compagnie->name" />
            </x-form>
        </div>
    </div>
</x-app-layout>