<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\CarRequest;
use App\Models\Department;
use App\Models\Recording;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

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

    public function setPeriod(string $period): void
    {
        $this->period = in_array($period, ['all', 'today', '24h', 'week', 'month'], true) ? $period : 'month';
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

    /** Base : sorties (Exit) de véhicules, jointes à la demande + demandeur + département. */
    private function exitsBase(?Carbon $since)
    {
        return DB::table('recordings')
            ->join('car_requests', 'recordings.requestable_id', '=', 'car_requests.id')
            ->join('users', 'car_requests.user_id', '=', 'users.id')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->where('recordings.action', 'Exit')
            ->where('recordings.requestable_type', CarRequest::class)
            ->when($since, fn ($q) => $q->where('recordings.created_at', '>=', $since));
    }

    public function render()
    {
        $since = $this->periodStart();

        // KPIs
        $exitsCount = (clone $this->exitsBase($since))->count();

        $entriesCount = Recording::query()
            ->where('action', 'Entry')
            ->where('requestable_type', CarRequest::class)
            ->when($since, fn ($q) => $q->where('created_at', '>=', $since))
            ->count();

        $currentlyOut = Recording::query()->vehiclesOut()->count();

        $distinctVehicles = (clone $this->exitsBase($since))
            ->whereNotNull('car_requests.car_number')
            ->distinct()
            ->count('car_requests.car_number');

        // Top véhicules par nombre de sorties (respecte le filtre département)
        $topVehicles = (clone $this->exitsBase($since))
            ->when($this->department !== '', fn ($q) => $q->where('users.department_id', $this->department))
            ->whereNotNull('car_requests.car_number')
            ->groupBy('car_requests.car_number')
            ->selectRaw('car_requests.car_number as label, COUNT(*) as total')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Sorties par département (vue d'ensemble — ignore le filtre département)
        $byDepartment = (clone $this->exitsBase($since))
            ->groupBy('departments.name')
            ->selectRaw("COALESCE(departments.name, 'Unassigned') as label, COUNT(*) as total")
            ->orderByDesc('total')
            ->get();

        // Sorties dans le temps (par jour) — borné aux 30 derniers jours si période = all
        $timeSince = $since ?? Carbon::now()->subDays(29)->startOfDay();
        $overTime = (clone $this->exitsBase($timeSince))
            ->selectRaw('CAST(recordings.created_at AS DATE) as d, COUNT(*) as total')
            ->groupBy(DB::raw('CAST(recordings.created_at AS DATE)'))
            ->orderBy('d')
            ->get();

        $departments = Department::orderBy('name')->get(['id', 'name']);

        return view('livewire.reports.offsite-report', compact(
            'exitsCount',
            'entriesCount',
            'currentlyOut',
            'distinctVehicles',
            'topVehicles',
            'byDepartment',
            'overTime',
            'departments',
        ));
    }
}
