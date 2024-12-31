@props(['icon', 'name'=>'','active' => false])

@php
$classes = $active ? 'menu-item active' : 'menu-item';
@endphp
<li {{ $attributes->merge(['class' => $classes]) }}>
    <a href="javascript:void(0)" class="menu-link menu-toggle">
        {{ $icon }}
        <div data-i18n="{{ $name }}">{{ $name }}</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item">
            {{ $slot }}
        </li>
    </ul>
</li>