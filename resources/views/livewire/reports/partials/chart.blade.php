{{-- Canvas Chart.js. Props: $config (array), $key (string unique, doit inclure
     les filtres pour forcer la recréation quand ils changent), $height. --}}
<div wire:key="chart-{{ $key }}" wire:ignore
    x-data="chartjs(@js($config))" style="height: {{ $height ?? '260px' }}">
    <canvas x-ref="canvas"></canvas>
</div>
