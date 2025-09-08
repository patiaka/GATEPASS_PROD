<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use App\Models\Recording;
use App\Models\CarRequest;
use Livewire\Attributes\Title;
use App\Models\MaterialRequest;
use Livewire\Attributes\Layout;
use App\Enum\MaterialRequestStatus;
use Illuminate\Support\Facades\Auth;

#[Title('Dashboard')]
final class Dashboard extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        // Checkout counts
        $car_check_out = Recording::whereHasMorph('requestable', [CarRequest::class])->count();
        $mat_check_out = Recording::whereHasMorph('requestable', [MaterialRequest::class])->count();

        // Helper pour récupérer un tableau de stats groupés par status
        $getStatusCounts = fn($model) =>
        $model::query()
            ->forUser()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Car requests (grouped)
        $carStats = $getStatusCounts(CarRequest::class);
        $car_request_all      = CarRequest::forUser()->count();
        $car_request_rejected = $carStats[MaterialRequestStatus::Rejected->value] ?? 0;
        $car_request_pending  = $carStats[MaterialRequestStatus::Pending->value]  ?? 0;
        $car_request_approved = $carStats[MaterialRequestStatus::Approved->value] ?? 0;

        // Material requests (grouped)
        $matStats = $getStatusCounts(MaterialRequest::class);
        $mat_request_all      = MaterialRequest::forUser()->count();
        $mat_request_rejected = $matStats[MaterialRequestStatus::Rejected->value] ?? 0;
        $mat_request_pending  = $matStats[MaterialRequestStatus::Pending->value]  ?? 0;
        $mat_request_approved = $matStats[MaterialRequestStatus::Approved->value] ?? 0;

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
            'car_check_out'
        ));
    }
}
