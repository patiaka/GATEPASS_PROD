<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Exports\OffsiteReportExport;
use App\Models\CarRequest;
use App\Models\Department;
use App\Models\MaterialRequest;
use App\Models\Recording;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Spatie\Browsershot\Browsershot;

use function compact;

#[Title('Offsite Records report')]
final class OffsiteReport extends Component
{
    /** all | today | 24h | week | month */
    #[Url]
    public string $period = 'month';

    /** Filtre département (id) — vide = tous. */
    #[Url]
    public string $department = '';

    /** Filtre porte — vide = toutes. */
    #[Url]
    public string $gate = '';

    /** Onglet actif : overview | checkouts | requests */
    #[Url]
    public string $tab = 'overview';

    public function setPeriod(string $period): void
    {
        $this->period = in_array($period, ['all', 'today', '24h', 'week', 'month'], true) ? $period : 'month';
    }

    public function setTab(string $tab): void
    {
        $this->tab = in_array($tab, ['overview', 'checkouts', 'requests'], true) ? $tab : 'overview';
    }

    /**
     * Prépare les segments d'un donut (top N + « Other ») avec pourcentage et couleur.
     *
     * @return array<int, array{label:string,total:int,percent:float,color:string}>
     */
    private function donut(Collection $data, int $top = 6): array
    {
        $sorted = $data->sortByDesc('total')->values();
        $segments = $sorted->take($top)->map(fn ($r) => ['label' => $r->label, 'total' => (int) $r->total])->all();

        $rest = $sorted->slice($top);
        if ($rest->isNotEmpty()) {
            $segments[] = ['label' => 'Other', 'total' => (int) $rest->sum('total')];
        }

        $sum = array_sum(array_column($segments, 'total')) ?: 1;
        $palette = ['#134169', '#2b7fbf', '#4aa3df', '#6cc0a0', '#f0a500', '#e0663f', '#64748b'];

        foreach ($segments as $i => &$s) {
            $s['percent'] = round($s['total'] / $sum * 100, 1);
            $s['color'] = $palette[$i % count($palette)];
        }

        return $segments;
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

    /** Base : mouvements de véhicules joints à la demande + demandeur + département. */
    private function movementsBase(string $action, ?Carbon $since, bool $withDepartment = true)
    {
        return DB::table('recordings')
            ->join('car_requests', 'recordings.requestable_id', '=', 'car_requests.id')
            ->join('users', 'car_requests.user_id', '=', 'users.id')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->where('recordings.action', $action)
            ->where('recordings.requestable_type', CarRequest::class)
            ->when($since, fn ($q) => $q->where('recordings.created_at', '>=', $since))
            ->when($this->gate !== '', fn ($q) => $q->where('recordings.gate', $this->gate))
            ->when($withDepartment && $this->department !== '', fn ($q) => $q->where('users.department_id', $this->department));
    }

    /**
     * Sorties (check-out) groupées par société ou par département, en combinant
     * VÉHICULE + MATÉRIEL. La société vient du champ « company » saisi
     * manuellement sur la demande.
     *
     * @param  'company'|'department'  $by
     */
    private function exitsGrouped(string $by, ?Carbon $since, bool $applyDeptFilter = true): Collection
    {
        $build = function (string $table, string $type) use ($by, $since, $applyDeptFilter) {
            $q = DB::table('recordings')
                ->join($table, 'recordings.requestable_id', '=', "$table.id")
                ->join('users', "$table.user_id", '=', 'users.id')
                ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
                ->where('recordings.action', 'Exit')
                ->where('recordings.requestable_type', $type)
                ->when($since, fn ($x) => $x->where('recordings.created_at', '>=', $since))
                ->when($this->gate !== '', fn ($x) => $x->where('recordings.gate', $this->gate))
                ->when($applyDeptFilter && $this->department !== '', fn ($x) => $x->where('users.department_id', $this->department));

            return $by === 'company'
                ? $q->groupBy("$table.company")
                    ->selectRaw("COALESCE(NULLIF(LTRIM(RTRIM($table.company)), ''), 'Unknown') as label, COUNT(*) as total")
                : $q->groupBy('departments.name')
                    ->selectRaw("COALESCE(departments.name, 'Unassigned') as label, COUNT(*) as total");
        };

        return $build('car_requests', CarRequest::class)->get()
            ->concat($build('material_requests', MaterialRequest::class)->get())
            ->groupBy('label')
            ->map(fn ($g) => (object) ['label' => $g->first()->label, 'total' => (int) $g->sum('total')])
            ->sortByDesc('total')
            ->values();
    }

    /**
     * Demandes CRÉÉES groupées par société ou par département, en combinant
     * véhicule + matériel (compte les demandes, pas les mouvements).
     *
     * @param  'company'|'department'  $by
     */
    private function requestsGrouped(string $by, ?Carbon $since, bool $applyDeptFilter = true): Collection
    {
        $build = function (string $table) use ($by, $since, $applyDeptFilter) {
            $q = DB::table($table)
                ->join('users', "$table.user_id", '=', 'users.id')
                ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
                ->when($since, fn ($x) => $x->where("$table.created_at", '>=', $since))
                ->when($applyDeptFilter && $this->department !== '', fn ($x) => $x->where('users.department_id', $this->department));

            return $by === 'company'
                ? $q->groupBy("$table.company")
                    ->selectRaw("COALESCE(NULLIF(LTRIM(RTRIM($table.company)), ''), 'Unknown') as label, COUNT(*) as total")
                : $q->groupBy('departments.name')
                    ->selectRaw("COALESCE(departments.name, 'Unassigned') as label, COUNT(*) as total");
        };

        return $build('car_requests')->get()
            ->concat($build('material_requests')->get())
            ->groupBy('label')
            ->map(fn ($g) => (object) ['label' => $g->first()->label, 'total' => (int) $g->sum('total')])
            ->sortByDesc('total')
            ->values();
    }

    /** Config Chart.js : barres horizontales (classement). */
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

    /** Config Chart.js : courbe d'aire (évolution). */
    private function lineConfig(Collection $overTime): array
    {
        return [
            'type' => 'line',
            'data' => [
                'labels' => $overTime->map(fn ($r) => Carbon::parse($r->d)->format('d/m'))->all(),
                'datasets' => [[
                    'label' => 'Exits',
                    'data' => $overTime->pluck('total')->map(fn ($v) => (int) $v)->all(),
                    'borderColor' => '#134169',
                    'backgroundColor' => 'rgba(19,65,105,0.15)',
                    'fill' => true,
                    'tension' => 0.35,
                    'pointRadius' => 2,
                    'pointBackgroundColor' => '#134169',
                ]],
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => ['legend' => ['display' => false]],
                'scales' => [
                    'y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0], 'grid' => ['color' => '#f1f5f9']],
                    'x' => ['grid' => ['display' => false]],
                ],
            ],
        ];
    }

    /** Config Chart.js : donut (répartition). */
    private function doughnutConfig(array $segments): array
    {
        return [
            'type' => 'doughnut',
            'data' => [
                'labels' => array_column($segments, 'label'),
                'datasets' => [[
                    'data' => array_map('intval', array_column($segments, 'total')),
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

    /** Toutes les données du rapport, partagées par l'affichage et les exports. */
    private function data(): array
    {
        $since = $this->periodStart();

        $exitsCount = (clone $this->movementsBase('Exit', $since))->count();
        $entriesCount = (clone $this->movementsBase('Entry', $since))->count();
        $currentlyOut = Recording::query()->vehiclesOut()->count();
        $distinctVehicles = (clone $this->movementsBase('Exit', $since))
            ->whereNotNull('car_requests.car_number')
            ->distinct()
            ->count('car_requests.car_number');

        $topVehicles = (clone $this->movementsBase('Exit', $since))
            ->whereNotNull('car_requests.car_number')
            ->groupBy('car_requests.car_number')
            ->selectRaw('car_requests.car_number as label, COUNT(*) as total')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top sociétés (véhicule + matériel), regroupées par nom saisi manuellement
        $topCompanies = $this->exitsGrouped('company', $since)->take(10);

        // Vue d'ensemble par département (véhicule + matériel), ignore le filtre département
        $byDepartment = $this->exitsGrouped('department', $since, applyDeptFilter: false);

        // Classements par CRÉATION de demande (véhicule + matériel)
        $topCompaniesReq = $this->requestsGrouped('company', $since)->take(10);
        $byDepartmentReq = $this->requestsGrouped('department', $since, applyDeptFilter: false);

        // Répartition (donut) des sorties par département
        $deptDonut = $this->donut($byDepartment);

        $timeSince = $since ?? Carbon::now()->subDays(29)->startOfDay();
        $overTime = (clone $this->movementsBase('Exit', $timeSince))
            ->selectRaw('CAST(recordings.created_at AS DATE) as d, COUNT(*) as total')
            ->groupBy(DB::raw('CAST(recordings.created_at AS DATE)'))
            ->orderBy('d')
            ->get();

        // Configs Chart.js (JSON passé aux canvas)
        $charts = [
            'overTime' => $this->lineConfig($overTime),
            'deptDonut' => $this->doughnutConfig($deptDonut),
            'topVehicles' => $this->barConfig($topVehicles, '#134169'),
            'topCompanies' => $this->barConfig($topCompanies, '#134169'),
            'byDepartment' => $this->barConfig($byDepartment, '#134169'),
            'topCompaniesReq' => $this->barConfig($topCompaniesReq, '#059669'),
            'byDepartmentReq' => $this->barConfig($byDepartmentReq, '#059669'),
        ];

        return compact('exitsCount', 'entriesCount', 'currentlyOut', 'distinctVehicles', 'topVehicles', 'topCompanies', 'byDepartment', 'topCompaniesReq', 'byDepartmentReq', 'deptDonut', 'overTime', 'charts');
    }

    /** Libellé lisible des filtres actifs (pour l'en-tête d'export). */
    private function filterLabel(): string
    {
        $periodLabels = ['all' => 'All time', 'today' => 'Today', '24h' => 'Last 24h', 'week' => 'This week', 'month' => 'This month'];
        $parts = ['Period: '.($periodLabels[$this->period] ?? $this->period)];
        if ($this->department !== '') {
            $parts[] = 'Department: '.(Department::find($this->department)?->name ?? $this->department);
        }
        if ($this->gate !== '') {
            $parts[] = 'Gate: '.$this->gate;
        }

        return implode('  |  ', $parts);
    }

    public function exportExcel()
    {
        $d = $this->data();

        return (new OffsiteReportExport([
            'Top vehicles' => [
                'headings' => ['Vehicle', 'Exits'],
                'rows' => $d['topVehicles']->map(fn ($r) => [$r->label, $r->total])->all(),
            ],
            'Top companies (check-outs)' => [
                'headings' => ['Company', 'Check-outs'],
                'rows' => $d['topCompanies']->map(fn ($r) => [$r->label, $r->total])->all(),
            ],
            'Top companies (requests)' => [
                'headings' => ['Company', 'Requests'],
                'rows' => $d['topCompaniesReq']->map(fn ($r) => [$r->label, $r->total])->all(),
            ],
            'By department (check-outs)' => [
                'headings' => ['Department', 'Check-outs'],
                'rows' => $d['byDepartment']->map(fn ($r) => [$r->label, $r->total])->all(),
            ],
            'By department (requests)' => [
                'headings' => ['Department', 'Requests'],
                'rows' => $d['byDepartmentReq']->map(fn ($r) => [$r->label, $r->total])->all(),
            ],
            'Daily exits' => [
                'headings' => ['Date', 'Exits'],
                'rows' => $d['overTime']->map(fn ($r) => [Carbon::parse($r->d)->format('d-m-Y'), $r->total])->all(),
            ],
        ]))->download('offsite-report-'.now()->format('Ymd-His').'.xlsx');
    }

    public function exportPdf()
    {
        $data = $this->data();
        $filters = $this->filterLabel();
        $html = view('reports.offsite-pdf', array_merge($data, ['filters' => $filters, 'generatedAt' => now()->format('d-m-Y H:i')]))->render();

        $path = storage_path('app/offsite-report-'.now()->format('Ymd-His').'.pdf');

        Browsershot::html($html)
            ->margins(10, 10, 10, 10)
            ->format('A4')
            ->showBackground()
            ->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        $departments = Department::orderBy('name')->get(['id', 'name']);

        return view('livewire.reports.offsite-report', array_merge($this->data(), compact('departments')));
    }
}
