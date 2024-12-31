@props(['url'=>'','name' => ''])
@php
$classes = Route::currentRouteName() == $url ? 'menu-item active' : 'menu-item';
@endphp

<li {{ $attributes->merge(['class' => $classes]) }}>
    <a href="{{ route($url) }}" {{ $attributes->merge(['class' => "menu-link"]) }}>
        {{ $slot }}
        <div data-i18n="Analytics">{{ $name }}</div>
    </a>
</li>