<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\MaterialRequestStatus;
use App\Models\CarRequest;
use App\Models\MaterialRequest;
use App\Models\Recording;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
final class Dashboard extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $auth = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | MATERIAL / CAR REQUEST COUNTS
        |--------------------------------------------------------------------------
        | Même logique de visibilité que les pages de listing (scopes
        | RequestVisibility) pour que chaque carte corresponde à sa liste.
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

                        // ⚡️ Sélection des colonnes par type
                        $morphTo->constrain([
                            CarRequest::class => function ($query) {
                                $query->select('id', 'company', 'reference', 'car_number', 'car_type');
                            },
                            MaterialRequest::class => function ($query) {
                                $query->select('id', 'reference', 'company'); // ✅ SAFE
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

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

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
            'mat_check_latest'
        ));
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

        // Pour les approbateurs, "Pending" = en attente de MON action
        // (même logique que les pages material.pending / car.pending)
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
