<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\CarRequest;
use App\Models\Department;
use App\Models\MaterialRequest;
use App\Support\Pdf;
use App\Support\SvgChart;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

use function compact;

#[Title('Offsite Records report')]
final class OffsiteReport extends Component
{
    /**
     * Sources de données : un « type » de demande = une table + une classe morph.
     * L'ordre fixe l'ordre des colonnes/séries dans tous les tableaux et graphiques.
     */
    private const SOURCES = [
        'vehicle' => ['table' => 'car_requests', 'type' => CarRequest::class, 'label' => 'Vehicle', 'color' => SvgChart::VEHICLE],
        'material' => ['table' => 'material_requests', 'type' => MaterialRequest::class, 'label' => 'Material', 'color' => SvgChart::MATERIAL],
    ];

    private const STATUSES = ['Pending', 'Progress', 'Approved', 'Rejected', 'Expired', 'Cancelled'];

    /** all | today | 24h | week | month */
    #[Url]
    public string $period = 'month';

    /** Filtre département (id) — vide = tous. */
    #[Url]
    public string $department = '';

    /** Filtre porte — vide = toutes. */
    #[Url]
    public string $gate = '';

    /** Périmètre : all | vehicle | material. */
    #[Url]
    public string $scope = 'all';

    /** Onglet actif : overview | checkouts | requests */
    #[Url]
    public string $tab = 'overview';

    public function setPeriod(string $period): void
    {
        $this->period = in_array($period, ['all', 'today', '24h', 'week', 'month'], true) ? $period : 'month';
    }

    public function setScope(string $scope): void
    {
        $this->scope = in_array($scope, ['all', 'vehicle', 'material'], true) ? $scope : 'all';
    }

    public function setTab(string $tab): void
    {
        $this->tab = in_array($tab, ['overview', 'checkouts', 'requests'], true) ? $tab : 'overview';
    }

    /** Génère et télécharge le rapport PDF, exactement dans le périmètre affiché. */
    public function exportPdf()
    {
        $data = $this->data();

        $html = view('reports.offsite-pdf', array_merge($data, [
            'svg' => $this->svgCharts($data),
            'filters' => $this->filterSummary(),
            'statusList' => self::STATUSES,
            'generatedAt' => Carbon::now(),
            'generatedBy' => auth()->user()?->name ?? '—',
        ]))->render();

        $name = 'offsite-report-'.$this->scope.'-'.$this->period.'-'.Carbon::now()->format('Ymd-His').'.pdf';
        $path = storage_path('app/'.$name);

        $footer = '<div style="width:100%;padding:0 9mm;font-family:Segoe UI,Arial,sans-serif;font-size:7px;'
            .'color:#94a3b8;display:flex;justify-content:space-between;">'
            .'<span>'.e(__('Offsite Records report')).' — '.e($this->filterSummary()['period']).'</span>'
            .'<span><span class="pageNumber"></span> / <span class="totalPages"></span></span></div>';

        Pdf::make($html)
            ->margins(10, 9, 14, 9)
            ->showBackground()
            ->showBrowserHeaderAndFooter()
            ->hideHeader()
            ->footerHtml($footer)
            ->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    // ── Rendu ──────────────────────────────────────────────────────────────

    public function render()
    {
        $data = $this->data();

        $charts = [
            'overTime' => $this->lineConfig($data['overTime']),
            'deptDonut' => $this->doughnutConfig($this->deptDonut($data['requestsByDept'])),
            'typeSplit' => $this->doughnutConfig($data['typeSplit']),
            'requestsByDept' => $this->groupedBarConfig($data['requestsByDept'], $data['sources']),
            'requestsByCompany' => $this->groupedBarConfig($data['requestsByCompany'], $data['sources']),
            'exitsByDept' => $this->groupedBarConfig($data['exitsByDept'], $data['sources']),
            'exitsByCompany' => $this->groupedBarConfig($data['exitsByCompany'], $data['sources']),
            'topVehicles' => $this->barConfig($data['topVehicles'], SvgChart::VEHICLE),
            'topMaterials' => $this->barConfig($data['topMaterials'], SvgChart::MATERIAL),
        ];

        return view('livewire.reports.offsite-report', array_merge($data, [
            'charts' => $charts,
            'statusList' => self::STATUSES,
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'periodLabel' => $this->periodLabel(),
        ]));
    }

    // ── Périmètre & période ────────────────────────────────────────────────

    /** @return array<string, array{table:string, type:string, label:string, color:string}> */
    private function sources(): array
    {
        return $this->scope === 'all'
            ? self::SOURCES
            : [$this->scope => self::SOURCES[$this->scope]];
    }

    private function has(string $key): bool
    {
        return array_key_exists($key, $this->sources());
    }

    private function periodStart(): ?Carbon
    {
        return match ($this->period) {
            'today' => Carbon::today(),
            '24h' => Carbon::now()->subDay(),
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            default => null,
        };
    }

    private function periodLabel(): string
    {
        return match ($this->period) {
            'today' => __('Today'),
            '24h' => __('Last 24 hours'),
            'week' => __('Last 7 days'),
            'month' => __('Last 30 days'),
            default => __('All time'),
        };
    }

    // ── Requêtes de base ───────────────────────────────────────────────────

    /** Mouvements (check-in / check-out) d'un type de demande, filtrés. */
    private function movements(string $key, string $action, ?Carbon $since)
    {
        $s = self::SOURCES[$key];

        return DB::table('recordings')
            ->join($s['table'], 'recordings.requestable_id', '=', $s['table'].'.id')
            ->join('users', $s['table'].'.user_id', '=', 'users.id')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->where('recordings.action', $action)
            ->where('recordings.requestable_type', $s['type'])
            ->when($since, fn ($q) => $q->where('recordings.created_at', '>=', $since))
            ->when($this->gate !== '', fn ($q) => $q->where('recordings.gate', $this->gate))
            ->when($this->department !== '', fn ($q) => $q->where('users.department_id', $this->department));
    }

    /** Demandes CRÉÉES d'un type, filtrées (le filtre porte ne s'applique pas). */
    private function requests(string $key, ?Carbon $since)
    {
        $s = self::SOURCES[$key];

        return DB::table($s['table'])
            ->join('users', $s['table'].'.user_id', '=', 'users.id')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->when($since, fn ($q) => $q->where($s['table'].'.created_at', '>=', $since))
            ->when($this->department !== '', fn ($q) => $q->where('users.department_id', $this->department));
    }

    /** Demandes dont le dernier mouvement est une sortie (toujours dehors). */
    private function currentlyOut(string $key): int
    {
        $type = self::SOURCES[$key]['type'];

        return DB::table('recordings')
            ->where('requestable_type', $type)
            ->where('action', 'Exit')
            ->whereIn('id', function ($sub) use ($type) {
                $sub->selectRaw('MAX(id)')
                    ->from('recordings')
                    ->where('requestable_type', $type)
                    ->groupBy('requestable_id');
            })
            ->count();
    }

    /**
     * Regroupe une métrique par libellé (département ou société) en gardant le
     * détail par type : c'est la base de toutes les vues « Véhicule vs Matériel ».
     *
     * @param  callable(string): \Illuminate\Database\Query\Builder  $builder
     * @param  'department'|'company'  $by
     * @return Collection<int, array{label:string, vehicle:int, material:int, total:int}>
     */
    private function splitBy(callable $builder, string $by, int $limit = 0): Collection
    {
        $rows = [];

        foreach ($this->sources() as $key => $s) {
            $table = $s['table'];

            $query = $by === 'company'
                ? $builder($key)
                    ->groupBy($table.'.company')
                    ->selectRaw("COALESCE(NULLIF(LTRIM(RTRIM({$table}.company)), ''), 'Unknown') as label, COUNT(*) as total")
                : $builder($key)
                    ->groupBy('departments.name')
                    ->selectRaw("COALESCE(departments.name, 'Unassigned') as label, COUNT(*) as total");

            foreach ($query->get() as $row) {
                $label = (string) $row->label;
                $rows[$label] ??= ['label' => $label, 'vehicle' => 0, 'material' => 0, 'total' => 0];
                $rows[$label][$key] += (int) $row->total;
                $rows[$label]['total'] += (int) $row->total;
            }
        }

        $out = collect(array_values($rows))->sortByDesc('total')->values();

        return $limit > 0 ? $out->take($limit)->values() : $out;
    }

    /**
     * Répartition des demandes par statut, pour chaque type du périmètre.
     *
     * @return array<string, array<string, int>>
     */
    private function statusBreakdown(?Carbon $since): array
    {
        $out = [];

        foreach ($this->sources() as $key => $s) {
            $counts = $this->requests($key, $since)
                ->groupBy($s['table'].'.status')
                ->selectRaw($s['table'].'.status as label, COUNT(*) as total')
                ->pluck('total', 'label');

            $out[$key] = collect(self::STATUSES)
                ->mapWithKeys(fn (string $status) => [$status => (int) ($counts[$status] ?? 0)])
                ->all();
        }

        return $out;
    }

    /**
     * Sorties par jour, une série par type, sur un axe de dates continu
     * (les jours sans mouvement valent 0 — la courbe reste lisible).
     *
     * @return array{labels: array<int, string>, series: array<int, array{key:string, label:string, color:string, data:array<int, int>}>}
     */
    private function exitsOverTime(?Carbon $since): array
    {
        $from = ($since ?? Carbon::now()->subDays(29))->copy()->startOfDay();
        $days = min(62, max(1, $from->diffInDays(Carbon::today()) + 1));

        $labels = [];
        $keys = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $from->copy()->addDays($i);
            $keys[] = $date->format('Y-m-d');
            $labels[] = $date->format('d/m');
        }

        $series = [];
        foreach ($this->sources() as $key => $s) {
            $rows = $this->movements($key, 'Exit', $from)
                ->selectRaw('CAST(recordings.created_at AS DATE) as d, COUNT(*) as total')
                ->groupBy(DB::raw('CAST(recordings.created_at AS DATE)'))
                ->get()
                ->mapWithKeys(fn ($r) => [Carbon::parse($r->d)->format('Y-m-d') => (int) $r->total]);

            $series[] = [
                'key' => $key,
                'label' => __($s['label']),
                'color' => $s['color'],
                'data' => array_map(fn (string $d) => (int) ($rows[$d] ?? 0), $keys),
            ];
        }

        return compact('labels', 'series');
    }

    // ── Données du rapport ─────────────────────────────────────────────────

    /** Toutes les données, partagées par l'affichage web et l'export PDF. */
    private function data(): array
    {
        $since = $this->periodStart();
        $sources = $this->sources();

        // Indicateurs par type + total consolidé
        $stats = [];
        foreach ($sources as $key => $s) {
            $stats[$key] = [
                'key' => $key,
                'label' => __($s['label']),
                'color' => $s['color'],
                'requests' => $this->requests($key, $since)->count(),
                'exits' => $this->movements($key, 'Exit', $since)->count(),
                'entries' => $this->movements($key, 'Entry', $since)->count(),
                'out' => $this->currentlyOut($key),
            ];
        }

        $totals = [
            'requests' => array_sum(array_column($stats, 'requests')),
            'exits' => array_sum(array_column($stats, 'exits')),
            'entries' => array_sum(array_column($stats, 'entries')),
            'out' => array_sum(array_column($stats, 'out')),
        ];

        // Demandes soumises : le cœur du rapport (chiffres par département)
        $requestsByDept = $this->splitBy(fn (string $k) => $this->requests($k, $since), 'department');
        $requestsByCompany = $this->splitBy(fn (string $k) => $this->requests($k, $since), 'company', 10);

        // Mouvements de sortie
        $exitsByDept = $this->splitBy(fn (string $k) => $this->movements($k, 'Exit', $since), 'department');
        $exitsByCompany = $this->splitBy(fn (string $k) => $this->movements($k, 'Exit', $since), 'company', 10);

        $statuses = $this->statusBreakdown($since);
        $overTime = $this->exitsOverTime($since);

        // Spécifique véhicule : parc et plaques les plus sorties
        $distinctVehicles = 0;
        $topVehicles = collect();
        if ($this->has('vehicle')) {
            // Les demandes « sans véhicule » ont une plaque vide : on les écarte
            // des classements de plaques (sinon une barre sans libellé apparaît).
            $plated = fn () => $this->movements('vehicle', 'Exit', $since)
                ->whereNotNull('car_requests.car_number')
                ->where('car_requests.car_number', '<>', '');

            $distinctVehicles = $plated()
                ->distinct()
                ->count('car_requests.car_number');

            $topVehicles = $plated()
                ->groupBy('car_requests.car_number')
                ->selectRaw('car_requests.car_number as label, COUNT(*) as total')
                ->orderByDesc('total')
                ->limit(10)
                ->get();
        }

        // Spécifique matériel : désignations les plus demandées (quantités)
        $topMaterials = collect();
        $materialQuantity = 0;
        if ($this->has('material')) {
            $items = fn () => DB::table('material_request_items')
                ->join('material_requests', 'material_request_items.material_request_id', '=', 'material_requests.id')
                ->join('users', 'material_requests.user_id', '=', 'users.id')
                ->when($since, fn ($q) => $q->where('material_requests.created_at', '>=', $since))
                ->when($this->department !== '', fn ($q) => $q->where('users.department_id', $this->department));

            $materialQuantity = (int) $items()->sum('material_request_items.quantity');

            $topMaterials = $items()
                ->groupBy('material_request_items.designation')
                ->selectRaw('material_request_items.designation as label, SUM(material_request_items.quantity) as total')
                ->orderByDesc('total')
                ->limit(10)
                ->get();
        }

        // Répartition des demandes par type (donut) — n'a de sens qu'en périmètre « all »
        $typeSplit = [];
        foreach ($stats as $s) {
            $typeSplit[] = ['label' => $s['label'], 'value' => $s['requests'], 'color' => $s['color']];
        }

        $topDept = $requestsByDept->first();

        return compact(
            'sources', 'stats', 'totals', 'since',
            'requestsByDept', 'requestsByCompany', 'exitsByDept', 'exitsByCompany',
            'statuses', 'overTime', 'topVehicles', 'topMaterials',
            'distinctVehicles', 'materialQuantity', 'typeSplit', 'topDept',
        );
    }

    // ── Graphiques web (Chart.js) ──────────────────────────────────────────

    /** Séries Chart.js « Véhicule vs Matériel » pour un tableau ventilé. */
    private function groupedBarConfig(Collection $rows, array $sources): array
    {
        $datasets = [];

        foreach ($sources as $key => $s) {
            $datasets[] = [
                'label' => __($s['label']),
                'data' => $rows->pluck($key)->map(fn ($v) => (int) $v)->all(),
                'backgroundColor' => $s['color'],
                'borderRadius' => 3,
                'maxBarThickness' => 16,
            ];
        }

        return [
            'type' => 'bar',
            'data' => ['labels' => $rows->pluck('label')->all(), 'datasets' => $datasets],
            'options' => [
                'indexAxis' => 'y',
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => ['legend' => ['display' => count($datasets) > 1, 'position' => 'top', 'labels' => ['boxWidth' => 12, 'font' => ['size' => 11]]]],
                'scales' => [
                    'x' => ['beginAtZero' => true, 'ticks' => ['precision' => 0], 'grid' => ['color' => '#f1f5f9']],
                    'y' => ['grid' => ['display' => false]],
                ],
            ],
        ];
    }

    /** Barres horizontales simples (classement mono-série). */
    private function barConfig(Collection $rows, string $color): array
    {
        return [
            'type' => 'bar',
            'data' => [
                'labels' => $rows->pluck('label')->all(),
                'datasets' => [[
                    'data' => $rows->pluck('total')->map(fn ($v) => (int) $v)->all(),
                    'backgroundColor' => $color,
                    'borderRadius' => 4,
                    'maxBarThickness' => 20,
                ]],
            ],
            'options' => [
                'indexAxis' => 'y',
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => ['legend' => ['display' => false]],
                'scales' => [
                    'x' => ['beginAtZero' => true, 'ticks' => ['precision' => 0], 'grid' => ['color' => '#f1f5f9']],
                    'y' => ['grid' => ['display' => false]],
                ],
            ],
        ];
    }

    private function lineConfig(array $overTime): array
    {
        $datasets = [];

        foreach ($overTime['series'] as $s) {
            $datasets[] = [
                'label' => $s['label'],
                'data' => $s['data'],
                'borderColor' => $s['color'],
                'backgroundColor' => $s['color'].'26',
                'fill' => true,
                'tension' => 0.35,
                'pointRadius' => 2,
                'pointBackgroundColor' => $s['color'],
            ];
        }

        return [
            'type' => 'line',
            'data' => ['labels' => $overTime['labels'], 'datasets' => $datasets],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => ['legend' => ['display' => count($datasets) > 1, 'position' => 'top', 'labels' => ['boxWidth' => 12, 'font' => ['size' => 11]]]],
                'scales' => [
                    'y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0], 'grid' => ['color' => '#f1f5f9']],
                    'x' => ['grid' => ['display' => false]],
                ],
            ],
        ];
    }

    private function doughnutConfig(array $segments): array
    {
        return [
            'type' => 'doughnut',
            'data' => [
                'labels' => array_column($segments, 'label'),
                'datasets' => [[
                    'data' => array_map('intval', array_column($segments, 'value')),
                    'backgroundColor' => array_column($segments, 'color'),
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ]],
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'cutout' => '60%',
                'plugins' => ['legend' => ['position' => 'right', 'labels' => ['boxWidth' => 12, 'font' => ['size' => 11]]]],
            ],
        ];
    }

    /** Donut de répartition par département, top 6 + « Other ». */
    private function deptDonut(Collection $rows): array
    {
        $segments = $rows->take(6)
            ->map(fn (array $r) => ['label' => $r['label'], 'value' => (int) $r['total']])
            ->values()
            ->all();

        $rest = $rows->slice(6);
        if ($rest->isNotEmpty()) {
            $segments[] = ['label' => __('Other'), 'value' => (int) $rest->sum('total')];
        }

        foreach ($segments as $i => &$segment) {
            $segment['color'] = SvgChart::PALETTE[$i % count(SvgChart::PALETTE)];
        }

        return $segments;
    }

    // ── Export PDF ─────────────────────────────────────────────────────────

    /** Graphiques SVG (rendu serveur) pour le PDF. */
    private function svgCharts(array $data): array
    {
        $series = array_map(
            fn (array $s) => ['label' => __($s['label']), 'color' => $s['color']],
            array_values($data['sources']),
        );

        $rowsFor = fn (Collection $rows) => $rows->map(fn (array $r) => [
            'label' => $r['label'],
            'values' => array_map(fn (string $k) => $r[$k], array_keys($data['sources'])),
        ])->all();

        $single = count($data['sources']) === 1;
        $soloColor = $single ? reset($data['sources'])['color'] : SvgChart::VEHICLE;

        $grouped = fn (Collection $rows, string $empty) => $single
            ? SvgChart::hbar(
                $rows->map(fn (array $r) => ['label' => $r['label'], 'value' => $r['total']])->all(),
                ['color' => $soloColor, 'empty' => $empty],
            )
            : SvgChart::groupedHBar($rowsFor($rows), $series, ['empty' => $empty]);

        $none = __('No data for this filter.');

        return [
            'requestsByDept' => $grouped($data['requestsByDept'], $none),
            'requestsByCompany' => $grouped($data['requestsByCompany'], $none),
            'exitsByDept' => $grouped($data['exitsByDept'], $none),
            'exitsByCompany' => $grouped($data['exitsByCompany'], $none),
            'deptDonut' => SvgChart::donut(
                $this->deptDonut($data['requestsByDept']),
                ['width' => 350, 'centerLabel' => __('Requests'), 'empty' => $none],
            ),
            'typeSplit' => SvgChart::donut(
                $data['typeSplit'],
                ['width' => 350, 'centerLabel' => __('Requests'), 'empty' => $none],
            ),
            'overTime' => SvgChart::line(
                $data['overTime']['labels'],
                $data['overTime']['series'],
                ['width' => 700, 'height' => 210, 'empty' => $none],
            ),
            'topVehicles' => SvgChart::hbar(
                $data['topVehicles']->map(fn ($r) => ['label' => $r->label, 'value' => (int) $r->total])->all(),
                ['color' => SvgChart::VEHICLE, 'empty' => $none],
            ),
            'topMaterials' => SvgChart::hbar(
                $data['topMaterials']->map(fn ($r) => ['label' => $r->label, 'value' => (int) $r->total])->all(),
                ['color' => SvgChart::MATERIAL, 'empty' => $none],
            ),
        ];
    }

    /** Rappel des filtres actifs, affiché en tête du PDF. */
    private function filterSummary(): array
    {
        $department = $this->department !== ''
            ? (Department::query()->whereKey($this->department)->value('name') ?? __('Unknown'))
            : __('All departments');

        return [
            'scope' => match ($this->scope) {
                'vehicle' => __('Vehicle'),
                'material' => __('Material'),
                default => __('Vehicle + Material'),
            },
            'period' => $this->periodLabel(),
            'department' => $department,
            'gate' => $this->gate !== '' ? $this->gate.' '.__('gate') : __('All gates'),
        ];
    }
}
