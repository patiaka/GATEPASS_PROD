<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Enum\MaterialRequestStatus;
use App\Models\CarRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

use function compact;

#[Title('Vehicle Off site Check In/Out')]
final class CarRequestCheckInCreate extends Component
{
    #[Validate('required|string|in:Approved,Rejected')]
    public string $decision = '';

    #[Validate('required|string|in:Exit,Entry')]
    public string $action = '';

    #[Validate('required|string|in:Back,Front')]
    public string $gate = '';

    #[Validate('nullable|string|in:25%,50%,75%,100%')]
    public string $fuel_level = '';

    #[Validate('nullable|integer')]
    public string $kilometers = '';

    #[Validate('required|exists:car_requests,id')]
    public $car_request_id = '';

    #[Validate('nullable|exists:users,id')]
    public $car_driver_id = '';

    public $car_driver_list = [];

    public ?CarRequest $carRequest = null;

    public $Kilometers_type = '';

    public function updatedCarRequestId($value)
    {
        if (! $value) {
            $this->carRequest = null;

            return;
        }

        $this->carRequest = CarRequest::query()
            ->with([
                'car_drivers:id,car_request_id,user_id',
                'passengers:id,car_request_id,user_id',
            ])->find($value);

        $this->car_driver_list = $this->carRequest ? User::select('id', 'name', 'badge_number')->whereIn('id', $this->carRequest->car_drivers->pluck('user_id'))->get() : collect();
    }

    public function mount()
    {
        $this->carRequest = null;
    }

    public function recordSecurityCheck()
    {

        $this->validate();
        $item = CarRequest::findOrFail($this->car_request_id);

        // Vérifier expiration
        if ($item->isExpired()) {
            flash()->success('request expired');

            return;
        }
        $kilometer = $this->Kilometers_type === 'Kilometers' ? $this->kilometers.'KM' : $this->kilometers.'H';
        $item->recordings()->create([
            'action' => $this->action,      // 'entry' ou 'exit'
            'gate' => $this->gate,
            'kilometers' => $kilometer,
            'fuel_level' => $this->fuel_level,
            'decision' => $this->decision,    // 'validated' ou 'rejected'
            'checked_at' => now(),
            'user_id' => Auth::user()->id,
            'car_driver_id' => $this->car_driver_id,
        ]);
        $this->reset();
        flash()->success('Record security check added');
    }

    public function render()
    {
        $carRequests = CarRequest::select('id', 'status', 'reference', 'car_number')->where('status', MaterialRequestStatus::Approved)->get();

        return view('livewire.car-request.car-request-check-in-create', compact('carRequests'));
    }
}
