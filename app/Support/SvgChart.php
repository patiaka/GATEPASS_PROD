<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Graphiques rendus en SVG pur, côté serveur.
 *
 * Les exports PDF passent par Browsershot : le HTML est rendu par un Chrome
 * headless qui n'a ni accès au bundle Vite (Chart.js) ni au réseau sur le
 * serveur de prod. Chaque graphique est donc généré ici en SVG — aucun script,
 * aucune police externe, aucune requête sortante.
 */
final class SvgChart
{
    public const VEHICLE = '#134169';

    public const MATERIAL = '#059669';

    /** Palette des donuts et des séries multiples. */
    public const PALETTE = ['#134169', '#2b7fbf', '#4aa3df', '#6cc0a0', '#f0a500', '#e0663f', '#8b5cf6', '#94a3b8'];

    // ── Barres horizontales (classements) ──────────────────────────────────

    /**
     * @param  array<int, array{label:string, value:int|float}>  $rows
     * @param  array{color?:string, width?:int, labelWidth?:int, rowHeight?:int, empty?:string}  $o
     */
    public static function hbar(array $rows, array $o = []): string
    {
        $rows = array_values($rows);
        $width = (int) ($o['width'] ?? 700);

        if ($rows === []) {
            return self::placeholder($width, $o['empty'] ?? 'No data');
        }

        $color = $o['color'] ?? self::VEHICLE;
        $labelW = (int) ($o['labelWidth'] ?? 165);
        $rowH = (int) ($o['rowHeight'] ?? 26);
        $pad = 6;
        $height = $pad * 2 + count($rows) * $rowH;

        $max = max(1.0, max(array_map(static fn ($r) => (float) $r['value'], $rows)));
        $trackX = $labelW + 10;
        $trackW = max(60, $width - $trackX - 56);

        $svg = self::open($width, $height);

        foreach ($rows as $i => $r) {
            $cy = $pad + $i * $rowH + $rowH / 2;
            $barH = $rowH - 10;
            $y = $cy - $barH / 2;
            $value = (float) $r['value'];
            $w = $value <= 0 ? 0.0 : max(3.0, $value / $max * $trackW);

            $svg .= '<rect x="'.$trackX.'" y="'.self::n($y).'" width="'.$trackW.'" height="'.$barH.'" rx="3" fill="#f1f5f9"/>';

            if ($w > 0) {
                $svg .= '<rect x="'.$trackX.'" y="'.self::n($y).'" width="'.self::n($w).'" height="'.$barH.'" rx="3" fill="'.$color.'"/>';
            }

            $svg .= self::text($labelW, $cy, self::clip((string) $r['label'], 28), ['anchor' => 'end', 'size' => 11, 'fill' => '#334155']);
            $svg .= self::text($trackX + $w + 7, $cy, self::num($value), ['size' => 11, 'fill' => '#0f172a', 'weight' => '700']);
        }

        return $svg.'</svg>';
    }

    /**
     * Barres groupées : une barre par série sur chaque ligne (ex. Véhicule vs Matériel).
     *
     * @param  array<int, array{label:string, values:array<int, int|float>}>  $rows
     * @param  array<int, array{label:string, color:string}>  $series
     * @param  array{width?:int, labelWidth?:int, empty?:string}  $o
     */
    public static function groupedHBar(array $rows, array $series, array $o = []): string
    {
        $rows = array_values($rows);
        $series = array_values($series);
        $width = (int) ($o['width'] ?? 700);

        if ($rows === [] || $series === []) {
            return self::placeholder($width, $o['empty'] ?? 'No data');
        }

        $labelW = (int) ($o['labelWidth'] ?? 165);
        $barH = 11;
        $gap = 4;
        $rowH = count($series) * ($barH + $gap) + 10;
        $legendH = 22;
        $pad = 6;
        $height = $legendH + $pad * 2 + count($rows) * $rowH;

        $max = 1.0;
        foreach ($rows as $r) {
            foreach ($r['values'] as $v) {
                $max = max($max, (float) $v);
            }
        }

        $trackX = $labelW + 10;
        $trackW = max(60, $width - $trackX - 56);

        $svg = self::open($width, $height);
        $svg .= self::legend($series, $trackX, 11);

        foreach ($rows as $i => $r) {
            $top = $legendH + $pad + $i * $rowH;
            $blockH = count($series) * ($barH + $gap) - $gap;
            $svg .= self::text($labelW, $top + $blockH / 2, self::clip((string) $r['label'], 28), ['anchor' => 'end', 'size' => 11, 'fill' => '#334155']);

            foreach ($series as $j => $s) {
                $v = (float) ($r['values'][$j] ?? 0);
                $y = $top + $j * ($barH + $gap);
                $w = $v <= 0 ? 0.0 : max(3.0, $v / $max * $trackW);

                $svg .= '<rect x="'.$trackX.'" y="'.self::n($y).'" width="'.$trackW.'" height="'.$barH.'" rx="2" fill="#f8fafc"/>';

                if ($w > 0) {
                    $svg .= '<rect x="'.$trackX.'" y="'.self::n($y).'" width="'.self::n($w).'" height="'.$barH.'" rx="2" fill="'.$s['color'].'"/>';
                }

                $svg .= self::text($trackX + $w + 6, $y + $barH / 2, self::num($v), ['size' => 9.5, 'fill' => '#475569', 'weight' => '600']);
            }
        }

        return $svg.'</svg>';
    }

    // ── Donut (répartition) ────────────────────────────────────────────────

    /**
     * @param  array<int, array{label:string, value:int|float, color?:string}>  $segments
     * @param  array{width?:int, size?:int, centerLabel?:string, empty?:string}  $o
     */
    public static function donut(array $segments, array $o = []): string
    {
        $segments = array_values(array_filter($segments, static fn ($s) => (float) $s['value'] > 0));
        $width = (int) ($o['width'] ?? 340);

        if ($segments === []) {
            return self::placeholder($width, $o['empty'] ?? 'No data');
        }

        $size = (int) ($o['size'] ?? 170);
        $rowH = 18;
        $height = max($size + 10, count($segments) * $rowH + 24);
        $cx = $size / 2 + 4;
        $cy = $height / 2;
        $r = $size / 2 - 16;
        $stroke = 26;
        $circ = 2 * M_PI * $r;
        $total = array_sum(array_map(static fn ($s) => (float) $s['value'], $segments)) ?: 1.0;

        $svg = self::open($width, $height);
        $svg .= '<g transform="rotate(-90 '.self::n($cx).' '.self::n($cy).')">';
        $svg .= '<circle cx="'.self::n($cx).'" cy="'.self::n($cy).'" r="'.self::n($r).'" fill="none" stroke="#f1f5f9" stroke-width="'.$stroke.'"/>';

        $offset = 0.0;
        foreach ($segments as $i => $s) {
            $len = (float) $s['value'] / $total * $circ;
            $color = $s['color'] ?? self::PALETTE[$i % count(self::PALETTE)];
            $svg .= '<circle cx="'.self::n($cx).'" cy="'.self::n($cy).'" r="'.self::n($r).'" fill="none"'
                .' stroke="'.$color.'" stroke-width="'.$stroke.'"'
                .' stroke-dasharray="'.self::n(max(0.5, $len - 1.5)).' '.self::n($circ).'"'
                .' stroke-dashoffset="'.self::n(-$offset).'"/>';
            $offset += $len;
        }

        $svg .= '</g>';
        $svg .= self::text($cx, $cy - 5, self::num($total), ['anchor' => 'middle', 'size' => 15, 'weight' => '700', 'fill' => '#0f172a']);
        $svg .= self::text($cx, $cy + 11, (string) ($o['centerLabel'] ?? 'Total'), ['anchor' => 'middle', 'size' => 9, 'fill' => '#64748b']);

        $lx = $size + 14;
        $ly = ($height - count($segments) * $rowH) / 2 + $rowH / 2;

        foreach ($segments as $i => $s) {
            $color = $s['color'] ?? self::PALETTE[$i % count(self::PALETTE)];
            $y = $ly + $i * $rowH;
            $pct = round((float) $s['value'] / $total * 100, 1);
            $svg .= '<rect x="'.self::n($lx).'" y="'.self::n($y - 5).'" width="9" height="9" rx="2" fill="'.$color.'"/>';
            $svg .= self::text($lx + 15, $y, self::clip((string) $s['label'], 18).'  '.self::num($s['value']).' ('.$pct.'%)', ['size' => 10, 'fill' => '#334155']);
        }

        return $svg.'</svg>';
    }

    // ── Courbes d'aire (évolution) ─────────────────────────────────────────

    /**
     * @param  array<int, string>  $labels
     * @param  array<int, array{label:string, color:string, data:array<int, int|float>}>  $series
     * @param  array{width?:int, height?:int, empty?:string}  $o
     */
    public static function line(array $labels, array $series, array $o = []): string
    {
        $labels = array_values($labels);
        $series = array_values($series);
        $width = (int) ($o['width'] ?? 700);
        $height = (int) ($o['height'] ?? 230);

        if ($labels === [] || $series === []) {
            return self::placeholder($width, $o['empty'] ?? 'No data');
        }

        $left = 40;
        $top = 28;
        $plotW = $width - $left - 12;
        $plotH = $height - $top - 26;

        $max = 1.0;
        foreach ($series as $s) {
            foreach ($s['data'] as $v) {
                $max = max($max, (float) $v);
            }
        }
        $max = self::niceMax($max);

        $n = count($labels);
        $x = static fn (int $i): float => $n <= 1 ? $left + $plotW / 2 : $left + $i * ($plotW / ($n - 1));
        $y = static fn (float $v): float => $top + $plotH - ($v / $max) * $plotH;

        $svg = self::open($width, $height);
        $svg .= self::legend($series, $left, 11);

        $divs = (int) min(4, max(1, $max));
        for ($i = 0; $i <= $divs; $i++) {
            $val = $max / $divs * $i;
            $gy = $y($val);
            $svg .= '<line x1="'.$left.'" y1="'.self::n($gy).'" x2="'.self::n($left + $plotW).'" y2="'.self::n($gy).'" stroke="#eef2f7" stroke-width="1"/>';
            $svg .= self::text($left - 6, $gy, self::num(round($val)), ['anchor' => 'end', 'size' => 9, 'fill' => '#94a3b8']);
        }

        foreach ($series as $s) {
            $pts = [];
            foreach (array_values($s['data']) as $i => $v) {
                $pts[] = self::n($x($i)).','.self::n($y((float) $v));
            }

            if ($pts === []) {
                continue;
            }

            $base = self::n($top + $plotH);
            $svg .= '<path d="M '.self::n($x(0)).','.$base.' L '.implode(' L ', $pts).' L '.self::n($x($n - 1)).','.$base.' Z" fill="'.$s['color'].'" fill-opacity="0.12"/>';
            $svg .= '<polyline points="'.implode(' ', $pts).'" fill="none" stroke="'.$s['color'].'" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>';

            if ($n <= 32) {
                foreach (array_values($s['data']) as $i => $v) {
                    $svg .= '<circle cx="'.self::n($x($i)).'" cy="'.self::n($y((float) $v)).'" r="2" fill="'.$s['color'].'"/>';
                }
            }
        }

        $step = max(1, (int) ceil($n / 12));
        foreach ($labels as $i => $label) {
            if ($i % $step !== 0 && $i !== $n - 1) {
                continue;
            }
            $svg .= self::text($x($i), $height - 9, (string) $label, ['anchor' => 'middle', 'size' => 9, 'fill' => '#94a3b8']);
        }

        return $svg.'</svg>';
    }

    // ── Primitives ─────────────────────────────────────────────────────────

    private static function open(int $w, float $h): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$w.' '.self::n($h).'"'
            .' preserveAspectRatio="xMidYMid meet"'
            ." style=\"display:block;width:100%;height:auto;font-family:'Segoe UI',Helvetica,Arial,sans-serif\">";
    }

    /** @param array{anchor?:string, size?:int|float, weight?:string, fill?:string} $o */
    private static function text(float $x, float $y, string $s, array $o = []): string
    {
        return '<text x="'.self::n($x).'" y="'.self::n($y).'"'
            .' fill="'.($o['fill'] ?? '#334155').'"'
            .' font-size="'.($o['size'] ?? 11).'"'
            .' font-weight="'.($o['weight'] ?? '400').'"'
            .' text-anchor="'.($o['anchor'] ?? 'start').'"'
            .' dominant-baseline="middle">'
            .htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
            .'</text>';
    }

    /** @param array<int, array{label:string, color:string}> $series */
    private static function legend(array $series, float $x, float $y): string
    {
        $out = '';

        foreach ($series as $s) {
            $label = (string) $s['label'];
            $out .= '<rect x="'.self::n($x).'" y="'.self::n($y - 5).'" width="9" height="9" rx="2" fill="'.$s['color'].'"/>';
            $out .= self::text($x + 14, $y, $label, ['size' => 10, 'fill' => '#475569']);
            $x += 26 + mb_strlen($label) * 5.6;
        }

        return $out;
    }

    private static function placeholder(int $w, string $msg): string
    {
        return self::open($w, 80)
            .self::text($w / 2, 40, $msg, ['anchor' => 'middle', 'size' => 11, 'fill' => '#94a3b8'])
            .'</svg>';
    }

    /** Arrondit le maximum de l'axe Y à une valeur « ronde » (1, 2, 5, 10, 20…). */
    private static function niceMax(float $max): float
    {
        $mag = 10 ** max(0, (int) floor(log10(max(1.0, $max))));

        foreach ([1, 1.5, 2, 2.5, 3, 4, 5, 6, 8, 10] as $s) {
            if ($max <= $s * $mag) {
                return (float) ($s * $mag);
            }
        }

        return (float) (10 * $mag);
    }

    private static function n(float $v): string
    {
        $s = number_format($v, 2, '.', '');

        return str_contains($s, '.') ? rtrim(rtrim($s, '0'), '.') : $s;
    }

    private static function num(int|float $v): string
    {
        return number_format((float) $v, 0, '.', ' ');
    }

    private static function clip(string $s, int $len): string
    {
        return mb_strlen($s) > $len ? mb_substr($s, 0, $len - 1).'…' : $s;
    }
}
