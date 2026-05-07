@props(['row' => ''])

@if ($row)
    @can('show-request', $row)
        <a aria-label="Show"
            {{ $attributes->merge([
                'class' => '
                                inline-flex items-center justify-center
                                w-7 h-7 rounded-md
                                border border-gray-200
                                bg-white
                                text-gray-500
                                hover:text-indigo-600
                                hover:border-indigo-600
                                hover:bg-indigo-50
                                transition
                            ',
            ]) }}>
            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
        </a>
    @endcan
@else
    <a aria-label="Show"
        {{ $attributes->merge([
            'class' => '
                        inline-flex items-center justify-center
                        w-7 h-7 rounded-md
                        border border-gray-200
                        bg-white
                        text-gray-500
                        hover:text-indigo-600
                        hover:border-indigo-600
                        hover:bg-indigo-50
                        transition
                    ',
        ]) }}>
        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
    </a>
@endif
