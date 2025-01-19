<x-app-layout>
    <div class="card">
        <div class="card-body">
            <x-form route="{{ route('document.update', ['document' => $document]) }}" type="update"
                url="{{ route('material.index') }}">
                <x-input type="file" name="image" />
            </x-form>
        </div>
    </div>
</x-app-layout>