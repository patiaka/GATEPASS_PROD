<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use App\Models\CarRequest;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Enum\MaterialRequestStatus;
use Illuminate\Support\Facades\Auth;

#[Title('Dashboard')]
final class Dashboard extends Component
{


    #[Layout('layouts.app')]
    public function render()
    {
        $auth = Auth::user();
        // $car_request_rejected = CarRequest::where('status', MaterialRequestStatus::Rejected)
        //     ->when($auth->isUser(), function ($query) use ($auth) {
        //         $query->where('user_id', $auth->id);
        //     })->count();

        // $car_request_pending = CarRequest::where('status', MaterialRequestStatus::Pending)
        //     ->when(auth()->user()->isAdmin() || auth()->user()->isSecurity(), function ($query) {
        //         $query->where('hod_approval_id', null);
        //     })
        //     ->count();

        // $car_request_approved = CarRequest::where('status', MaterialRequestStatus::Approved)
        //     ->when(auth()->user()->isAdmin() || auth()->user()->isSecurity(), function ($query) {
        //         $query->where('hod_approval_id', null);
        //     })
        //     ->count();
        return view('livewire.dashboard');
    }
}
