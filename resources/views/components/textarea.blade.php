@props(['value'=>'', 'label'=>'', 'place'=>'', 'required'=> true, 'name'=>''])

<div class="mb-4">
    <label for="{{ $name }}" {{ $attributes->merge(['class' => 'block text-sm font-medium text-gray-700 uppercase
        mb-1']) }}>
        @empty($label)
        {{ Str::headline($name) }}
        @else
        {{ $label }}
        @endempty
        @if($required) <span class="text-red-500">*</span> @endif
    </label>

    <textarea @required($required) name="{{ $name }}" id="{{ $name }}" rows="4" placeholder="{{ $place }}" {{
        $attributes->merge(['class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400']) }}
    >{{ $value }}</textarea>

    @if ($errors->get($name))
    <ul class="mt-1 text-sm text-red-600 space-y-1">
        @foreach ((array) $errors->get($name) as $message)
        <li>{{ $message }}</li>
        @endforeach
    </ul>
    @endif
</div>