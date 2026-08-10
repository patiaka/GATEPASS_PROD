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
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687 1.687M7 17l4-1 9-9a2.121 2.121 0 00-3-3l-9 9-1 4z" />
            </svg>
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
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687 1.687M7 17l4-1 9-9a2.121 2.121 0 00-3-3l-9 9-1 4z" />
        </svg>
    </a>
@endif