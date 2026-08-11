<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\MaterialRequestStatus;
use App\Models\CarRequest;
use App\Models\Department;
use App\Models\MaterialRequest;
use App\Models\Recording;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Dashboard')]
final class Dashboard extends Component
{
    /** Période des statistiques en jours (7 / 14 / 30). */
    #[Url(as: 'period')]
    public int $stat_period = 14;

    /** Département filtré (uniquement pour Admin/GM/Director). */
    #[Url(as: 'dept')]
    public string $stat_department = '';

    /** Type de demande : all / material / vehicle. */
    #[Url(as: 'type')]
    public string $stat_type = 'all';

    #[Layout('layouts.app')]
    public function render()
    {
        $auth = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | MATERIAL / CAR REQUEST COUNTS (cartes du haut — inchangé)
        |--------------------------------------------------------------------------
        */
        [
            'all' => $mat_request_all,
            'approved' => $mat_request_approved,
            'pending' => $mat_request_pending,
            'rejected' => $mat_request_rejected,
        ] = $this->requestStats(MaterialRequest::query(), $auth);

        [
            'all' => $car_request_all,
            'approved' => $car_request_approved,
            'pending' => $car_request_pending,
            'rejected' => $car_request_rejected,
        ] = $this->requestStats(CarRequest::query(), $auth);

        /*
        |--------------------------------------------------------------------------
        | CHECKOUT COUNTS
        |--------------------------------------------------------------------------
        */
        $checkoutByType = function (string $class) {

            return Recording::query()
                ->with([
                    'user',
                    'car_driver:id,name,department_id',
                    'car_driver.department:id,name',

                    'requestable' => function ($morphTo) {
                        $morphTo->morphWith([
                            CarRequest::class => [],
                            MaterialRequest::class => [],
                        ]);

                        $morphTo->constrain([
                            CarRequest::class => function ($query) {
                                $query->select('id', 'company', 'reference', 'car_number', 'car_type');
                            },
                            MaterialRequest::class => function ($query) {
                                $query->select('id', 'reference', 'company');
                            },
                        ]);
                    },
                ])
                ->whereHasMorph('requestable', [$class]);
        };

        $carCheckouts = $checkoutByType(CarRequest::class);
        $matCheckouts = $checkoutByType(MaterialRequest::class);

        $car_check_out = $carCheckouts->count();
        $mat_check_out = $matCheckouts->count();

        $canSeeCheckouts = $auth->isGm() || $auth->isDirector() || $auth->isHod() || $auth->isAdmin() || $auth->isSecurity();
        $car_check_latest = $canSeeCheckouts ? $carCheckouts->latest()->limit(10)->get() : [];
        $mat_check_latest = $canSeeCheckouts ? $matCheckouts->latest()->limit(10)->get() : [];

        // Véhicules actuellement dehors : dernier mouvement = Exit (pas encore rentrés)
        $vehicles_out = $canSeeCheckouts ? $this->vehiclesCurrentlyOut() : collect();

        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES (visibles par tous, limitées au périmètre + filtres)
        |--------------------------------------------------------------------------
        */
        $scope = $this->resolveScope($auth);

        // Période : 7 / 14 / 30 jours (granularité jour) ou 1 an (granularité mois).
        $isYear = (int) $this->stat_period === 365;
        $days = in_array($this->stat_period, [7, 14, 30, 365], true) ? $this->stat_period : 14;
        $since = $isYear
            ? Carbon::today()->startOfMonth()->subMonthsNoOverflow(11)
            : Carbon::today()->subDays($days - 1);
        $periodLabel = $isYear ? '12 months' : $days.' days';

        $typeClasses = match ($this->stat_type) {
            'material' => [MaterialRequest::class],
            'vehicle' => [CarRequest::class],
            default => [MaterialRequest::class, CarRequest::class],
        };
        $models = match ($this->stat_type) {
            'material' => [new MaterialRequest],
            'vehicle' => [new CarRequest],
            default => [new MaterialRequest, new CarRequest],
        };

        $gate_traffic = $this->gateTraffic($scope['userIds'], $since, $typeClasses);
        $daily_traffic = $isYear
            ? $this->monthlyTraffic($scope['userIds'], $since, $typeClasses)
            : $this->dailyTraffic($scope['userIds'], $since, $days, $typeClasses);
        // Les demandes sont cumulatives/rares : pas de filtre période (sinon presque
        // tout est masqué). Seuls le périmètre et le type s'appliquent ici.
        $dept_requests = $this->requestsByDepartment($scope['deptIds'], $models);

        // Filtre département : liste des départements que l'utilisateur peut choisir
        $canFilterDepartment = $auth->isAdmin() || $auth->isGm() || $auth->isDirector();
        $filterDepartments = ! $canFilterDepartment
            ? collect()
            : ($auth->isAdmin() || $auth->isGm()
                ? Department::orderBy('name')->get(['id', 'name'])
                : Department::where('director_id', $auth->id)->orderBy('name')->get(['id', 'name']));

        return view('livewire.dashboard', compact(
            'car_request_all',
            'car_request_rejected',
            'car_request_pending',
            'car_request_approved',
            'mat_request_all',
            'mat_request_rejected',
            'mat_request_pending',
            'mat_request_approved',
            'mat_check_out',
            'car_check_out',
            'car_check_latest',
            'mat_check_latest',
            'gate_traffic',
            'daily_traffic',
            'periodLabel',
            'dept_requests',
            'canFilterDepartment',
            'filterDepartments',
            'vehicles_out'
        ));
    }

    /**
     * Véhicules dont le dernier passage est une "Exit" (donc encore hors site).
     */
    private function vehiclesCurrentlyOut()
    {
        return Recording::query()
            ->vehiclesOut()
            ->with(['car_driver:id,name', 'requestable'])
            ->get();
    }

    /**
     * Périmètre de l'utilisateur : départements (deptIds) et utilisateurs (userIds).
     * null = aucune restriction (voit tout).
     *
     * @return array{deptIds: ?array<int,int>, userIds: ?array<int,int>}
     */
    private function resolveScope(User $auth): array
    {
        if ($auth->isAdmin() || $auth->isGm()) {
            $deptIds = null; // tout
        } elseif ($auth->isDirector()) {
            $deptIds = Department::where('director_id', $auth->id)->pluck('id')->map(fn ($i) => (int) $i)->all();
        } else {
            // HOD / User : leur propre département
            $deptIds = $auth->department_id ? [(int) $auth->department_id] : [];
        }

        // Filtre département sélectionné (si autorisé / dans le périmètre)
        if ($this->stat_department !== '') {
            $selected = (int) $this->stat_department;
            if ($deptIds === null || in_array($selected, $deptIds, true)) {
                $deptIds = [$selected];
            }
        }

        $userIds = $deptIds === null
            ? null
            : User::whereIn('department_id', $deptIds)->pluck('id')->map(fn ($i) => (int) $i)->all();

        return ['deptIds' => $deptIds, 'userIds' => $userIds];
    }

    /**
     * Répartition des passages par porte, dans le périmètre + filtres.
     *
     * @param  array<int,int>|null  $userIds
     * @param  array<int,string>  $typeClasses
     */
    private function gateTraffic(?array $userIds, Carbon $since, array $typeClasses)
    {
        $counts = Recording::query()
            ->whereNotNull('gate')
            ->where('created_at', '>=', $since)
            ->whereHasMorph('requestable', $typeClasses, function ($q) use ($userIds) {
                if ($userIds !== null) {
                    $q->whereIn('user_id', $userIds);
                }
            })
            ->selectRaw('gate, COUNT(*) as total')
            ->groupBy('gate')
            ->pluck('total', 'gate');

        return collect(['Front', 'Back', 'Airport'])
            ->mapWithKeys(fn ($gate) => [$gate => (int) ($counts[$gate] ?? 0)]);
    }

    /**
     * Passages par jour sur la période (séries complètes, zéros inclus).
     *
     * @param  array<int,int>|null  $userIds
     * @param  array<int,string>  $typeClasses
     */
    private function dailyTraffic(?array $userIds, Carbon $since, int $days, array $typeClasses)
    {
        $raw = Recording::query()
            ->where('created_at', '>=', $since)
            ->whereHasMorph('requestable', $typeClasses, function ($q) use ($userIds) {
                if ($userIds !== null) {
                    $q->whereIn('user_id', $userIds);
                }
            })
            ->selectRaw('CAST(created_at AS DATE) as day, COUNT(*) as total')
            ->groupBy(DB::raw('CAST(created_at AS DATE)'))
            ->get()
            ->mapWithKeys(fn ($row) => [Carbon::parse($row->day)->format('Y-m-d') => (int) $row->total]);

        return collect(range(0, $days - 1))->map(function ($i) use ($since, $raw) {
            $date = $since->copy()->addDays($i);
            $key = $date->format('Y-m-d');

            return [
                'label' => $date->format('d/m'),
                'date' => $key,
                'total' => (int) ($raw[$key] ?? 0),
            ];
        });
    }

    /**
     * Passages par mois sur 12 mois (séries complètes, zéros inclus).
     * Utilisé pour la période « 1 an » — 12 barres au lieu de 365.
     *
     * @param  array<int,int>|null  $userIds
     * @param  array<int,string>  $typeClasses
     */
    private function monthlyTraffic(?array $userIds, Carbon $since, array $typeClasses)
    {
        $raw = Recording::query()
            ->where('created_at', '>=', $since)
            ->whereHasMorph('requestable', $typeClasses, function ($q) use ($userIds) {
                if ($userIds !== null) {
                    $q->whereIn('user_id', $userIds);
                }
            })
            ->selectRaw("CONVERT(char(7), created_at, 126) as ym, COUNT(*) as total")
            ->groupBy(DB::raw("CONVERT(char(7), created_at, 126)"))
            ->get()
            ->mapWithKeys(fn ($row) => [$row->ym => (int) $row->total]);

        return collect(range(0, 11))->map(function ($i) use ($since, $raw) {
            $date = $since->copy()->addMonthsNoOverflow($i);
            $key = $date->format('Y-m');

            return [
                'label' => $date->format('M'),
                'date' => $date->format('m/Y'),
                'total' => (int) ($raw[$key] ?? 0),
            ];
        });
    }

    /**
     * Nombre de demandes par département (top 8), dans le périmètre + filtre type.
     * Volontairement NON filtré par période (demandes cumulatives/rares).
     *
     * @param  array<int,int>|null  $deptIds
     * @param  array<int, MaterialRequest|CarRequest>  $models
     */
    private function requestsByDepartment(?array $deptIds, array $models)
    {
        $counts = [];

        foreach ($models as $model) {
            $table = $model->getTable();

            $rows = $model->newQuery()
                ->join('users', 'users.id', '=', $table.'.user_id')
                ->join('departments', 'departments.id', '=', 'users.department_id')
                ->when($deptIds !== null, fn ($q) => $q->whereIn('users.department_id', $deptIds))
                ->selectRaw('departments.name as dept, COUNT(*) as total')
                ->groupBy('departments.name')
                ->pluck('total', 'dept');

            foreach ($rows as $dept => $total) {
                $counts[$dept] = ($counts[$dept] ?? 0) + (int) $total;
            }
        }

        arsort($counts);

        return collect(array_slice($counts, 0, 8, true));
    }

    /**
     * @return array{all: int, approved: int, pending: int, rejected: int}
     */
    private function requestStats(Builder $query, User $auth): array
    {
        $byStatus = (clone $query)
            ->visibleTo($auth)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $pending = $auth->isApprover()
            ? (clone $query)->awaitingApprovalBy($auth)->count()
            : (int) ($byStatus[MaterialRequestStatus::Pending->value] ?? 0);

        return [
            'all' => (int) $byStatus->sum(),
            'approved' => (int) ($byStatus[MaterialRequestStatus::Approved->value] ?? 0),
            'pending' => $pending,
            'rejected' => (int) ($byStatus[MaterialRequestStatus::Rejected->value] ?? 0),
        ];
    }
}
