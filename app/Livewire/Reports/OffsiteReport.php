<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\Department;
use App\Reports\OffsiteReportData;
use App\Support\Pdf;
use App\Support\SvgChart;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

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

    /** Périmètre : all | vehicle | material. */
    #[Url]
    public string $scope = 'all';

    /** Onglet actif : overview | checkouts | requests */
    #[Url]
    public string $tab = 'overview';

    public function setPeriod(string $period): void
    {
        $this->period = in_array($period, OffsiteReportData::PERIODS, true) ? $period : 'month';
    }

    public function setScope(string $scope): void
    {
        $this->scope = in_array($scope, OffsiteReportData::SCOPES, true) ? $scope : 'all';
    }

    public function setTab(string $tab): void
    {
        $this->tab = in_array($tab, ['overview', 'checkouts', 'requests'], true) ? $tab : 'overview';
    }

    /** Filtres courants, tels que passés à la page d'impression. */
    public function filters(): array
    {
        return [
            'period' => $this->period,
            'department' => $this->department,
            'gate' => $this->gate,
            'scope' => $this->scope,
        ];
    }

    /**
     * Génère le PDF côté serveur (Chrome headless) et le télécharge.
     *
     * Dépend de Node + d'un navigateur sur le serveur ; la page d'impression
     * (reports.offsite.print) rend le même document sans cette dépendance.
     */
    public function exportPdf()
    {
        $report = $this->report();

        $html = view('reports.offsite-pdf', $report->documentData())->render();
        $path = storage_path('app/'.$report->fileName());

        $footer = '<div style="width:100%;padding:0 9mm;font-family:Segoe UI,Arial,sans-serif;font-size:7px;'
            .'color:#94a3b8;display:flex;justify-content:space-between;">'
            .'<span>'.e(__('Offsite Records report')).' — '.e($report->periodLabel()).'</span>'
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

    public function render()
    {
        $report = $this->report();
        $data = $report->data();

        $charts = [
            'overTime' => $this->lineConfig($data['overTime']),
            'deptDonut' => $this->doughnutConfig($report->deptDonut($data['requestsByDept'])),
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
            'statusList' => OffsiteReportData::STATUSES,
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'periodLabel' => $report->periodLabel(),
            'printUrl' => route('reports.offsite.print', $this->filters()),
        ]));
    }

    private function report(): OffsiteReportData
    {
        return OffsiteReportData::fromFilters($this->filters());
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
}
