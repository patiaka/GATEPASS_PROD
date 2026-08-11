{{-- Canvas Chart.js. Props: $config (array), $key (string), $height.
     Le wire:key inclut les filtres pour forcer la recréation quand ils changent. --}}
<div wire:key="chart-{{ $key }}-{{ $tab }}-{{ $period }}-{{ $department }}-{{ $gate }}" wire:ignore
    x-data="chartjs(@js($config))" style="height: {{ $height ?? '260px' }}">
    <canvas x-ref="canvas"></canvas>
</div>
