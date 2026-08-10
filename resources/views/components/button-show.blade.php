@props(['row' => ''])

@if ($row)
    @can('show-request', $row)
        <a aria-label="Show" title="View details"
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
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1 1 0 010-.644C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </a>
    @endcan
@else
    <a aria-label="Show" title="View details"
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
