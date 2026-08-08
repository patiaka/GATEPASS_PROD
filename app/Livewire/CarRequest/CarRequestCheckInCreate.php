<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Enum\MaterialRequestStatus;
use App\Models\CarRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

use function compact;

#[Title('Vehicle Off site Check In/Out')]
final class CarRequestCheckInCreate extends Component
{
    #[Validate('required|string|in:Exit,Entry')]
    public string $action = '';

    #[Validate('required|string|in:Back,Front,Airport')]
    public string $gate = '';

    #[Validate('nullable|string|in:25%,50%,75%,100%')]
    public string $fuel_level = '';

    #[Validate('nullable|integer')]
    public string $kilometers = '';

    #[Url(as: 'request')]
    #[Validate('required|exists:car_requests,id')]
    public $car_request_id = '';

    #[Validate('nullable|exists:users,id')]
    public $car_driver_id = '';

    public $car_driver_list = [];

    public ?CarRequest $carRequest = null;

    public $Kilometers_type = '';

    public ?string $last_movement = null;

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

        // Suggérer automatiquement le mouvement opposé au dernier enregistré
        $last = $this->carRequest?->recordings()->latest('id')->first();
        $this->last_movement = $last
            ? "{$last->action} — {$last->gate} gate — {$last->created_at->format('Y-m-d H:i')}"
            : null;
        $this->action = $last?->action === 'Exit' ? 'Entry' : 'Exit';
    }

    public function mount()
    {
        $this->carRequest = null;

        // Présélection depuis le bouton "Record" des listes (?request=ID)
        $id = $this->car_request_id ?: request('request');
        if ($id) {
            $this->car_request_id = (string) $id;
            $this->updatedCarRequestId($id);
        }
    }

    public function recordSecurityCheck()
    {

        $this->validate();
        $item = CarRequest::findOrFail($this->car_request_id);

        // Vérifier expiration
        if ($item->isExpired()) {
            flash()->error('This request has expired.');

            return;
        }

        // Empêcher deux mouvements identiques consécutifs (pas 2 Exit ni 2 Entry de suite)
        $lastAction = $item->recordings()->latest('id')->value('action');
        if ($lastAction === $this->action) {
            flash()->error("This request was already recorded as '{$this->action}'. Please record the opposite movement first.");

            return;
        }

        $kilometer = $this->kilometers !== ''
            ? $this->kilometers.($this->Kilometers_type === 'Kilometers' ? 'KM' : 'H')
            : null;

        $item->recordings()->create([
            'action' => $this->action,      // 'Entry' ou 'Exit'
            'gate' => $this->gate,
            'kilometers' => $kilometer,
            'fuel_level' => $this->fuel_level ?: null,
            // Un mouvement enregistré = passage validé (colonne NOT NULL en base)
            'decision' => 'Approved',
            'checked_at' => now(),
            'user_id' => Auth::user()->id,
            // '' provoquerait une violation de clé étrangère sur SQL Server
            'car_driver_id' => $this->car_driver_id ?: null,
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
