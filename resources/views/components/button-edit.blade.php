@props(['row' => ''])

@if ($row)
    @can('update-request', $row)
        <a aria-label="Edit" {{ $attributes->merge([
            'class' => '
            inline-flex items-center justify-center
            w-7 h-7 rounded-md
            border border-gray-200
            bg-white
            text-gray-500
            hover:text-blue-600
            hover:border-blue-600
            hover:bg-blue-50
            transition
            ',
            ]) }}>
            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
        </a>
    @endcan
@else
    <a aria-label="Edit" {{ $attributes->merge([
        'class' => '
        inline-flex items-center justify-center
        w-7 h-7 rounded-md
        border border-gray-200
        bg-white
        text-gray-500
        hover:text-blue-600
        hover:border-blue-600
        hover:bg-blue-50
        transition
        ',
        ]) }}>
        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
    </a>
@endif