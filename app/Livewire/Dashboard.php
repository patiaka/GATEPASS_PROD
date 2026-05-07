<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\MaterialRequestStatus;
use App\Models\CarRequest;
use App\Models\MaterialRequest;
use App\Models\Recording;
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
        | MATERIAL REQUEST COUNTS (filtrés par rôle)
        |--------------------------------------------------------------------------
        */
        $materialQuery = MaterialRequest::query()
            ->when($auth->isGm(), function ($query) use ($auth) {
                $query->where(function ($q) use ($auth) {
                    $q->where('status', MaterialRequestStatus::Progress)
                        ->whereNotNull('hod_approval_id')
                        ->orWhere('gm_approval_id', $auth->id)
                        ->orWhere('user_id', $auth->id);
                });
            })
            ->when($auth->isHod(), function ($query) use ($auth) {
                $auth->loadMissing('department');
                $users = $auth->department->loadMissing('users');
                $query->where(function ($q) use ($users, $auth) {
                    $q->where('status', MaterialRequestStatus::Pending)
                        ->whereIn('user_id', $users->users->pluck('id'))
                        ->orWhere('user_id', $auth->id)
                        ->orWhere('hod_approval_id', $auth->id);
                });
            })
            ->when($auth->isUser(), fn ($query) => $query->where('user_id', $auth->id))
            ->when($auth->isSecurity(), fn ($query) => $query->where('status', MaterialRequestStatus::Approved)
                ->orWhere('user_id', $auth->id));

        $matStats = (clone $materialQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $mat_request_all = (clone $materialQuery)->count();
        $mat_request_rejected = $matStats[MaterialRequestStatus::Rejected->value] ?? 0;
        $mat_request_pending = $matStats[MaterialRequestStatus::Pending->value] ?? 0;
        $mat_request_approved = $matStats[MaterialRequestStatus::Approved->value] ?? 0;

        /*
        |--------------------------------------------------------------------------
        | CAR REQUEST COUNTS (filtrés par rôle)
        |--------------------------------------------------------------------------
        */
        $carQuery = CarRequest::query()
            ->when($auth->isGm(), function ($query) use ($auth) {
                $query->where(function ($q) use ($auth) {
                    $q->where('status', MaterialRequestStatus::Progress)
                        ->whereNotNull('hod_approval_id')
                        ->orWhere('gm_approval_id', $auth->id)
                        ->orWhere('user_id', $auth->id);
                });
            })
            ->when($auth->isHod(), function ($query) use ($auth) {
                $auth->loadMissing('department');
                $users = $auth->department->loadMissing('users');
                $query->where(function ($q) use ($users, $auth) {
                    $q->where('status', MaterialRequestStatus::Pending)
                        ->whereIn('user_id', $users->users->pluck('id'))
                        ->orWhere('user_id', $auth->id)
                        ->orWhere('hod_approval_id', $auth->id);
                });
            })
            ->when($auth->isUser(), fn ($query) => $query->where('user_id', $auth->id))
            ->when($auth->isSecurity(), fn ($query) => $query->where('status', MaterialRequestStatus::Approved)
                ->orWhere('user_id', $auth->id));

        $carStats = (clone $carQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $car_request_all = (clone $carQuery)->count();
        $car_request_rejected = $carStats[MaterialRequestStatus::Rejected->value] ?? 0;
        $car_request_pending = $auth->isGm()
            ? ($carStats[MaterialRequestStatus::Progress->value] ?? 0)
            : ($carStats[MaterialRequestStatus::Pending->value] ?? 0);
        $car_request_approved = $carStats[MaterialRequestStatus::Approved->value] ?? 0;

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

        $car_check_latest = $auth->isGm() || $auth->isHod() || $auth->isAdmin() ? $carCheckouts->latest()->limit(10)->get() : [];
        $mat_check_latest = $auth->isGm() || $auth->isHod() || $auth->isAdmin() ? $matCheckouts->latest()->limit(10)->get() : [];

        // dd($car_check_latest);

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
}
