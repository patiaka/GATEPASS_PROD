<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use const false;

use App\Helper\RepeatInputAction;
use App\Livewire\Forms\CarRequestForm;
use App\Models\CarRequest;
use App\Models\Setting;
use App\Models\User;
use Gate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit vehicle request')]
final class CarRequestUpdate extends Component
{
    use RepeatInputAction;

    public CarRequest $carRequest;

    public CarRequestForm $form;

    public bool $date_long = false;

    public function getShowDestinationProperty(): bool
    {
        return $this->form->getShowDestinationField();
    }

    public function updatedFormStart()
    {
        $this->calculateEndDate();
    }

    public function updatedDateLong()
    {
        $this->calculateEndDate();
    }

    public function getShowVehicleFieldsProperty(): bool
    {
        return $this->form->getShowVehicleField();
    }

    public function mount(CarRequest $CarRequest)
    {
        $this->carRequest = $CarRequest;

        Gate::authorize('update-request', $this->carRequest);

        // setCarRequest charge aussi driver_ids / passenger_ids depuis les relations
        $this->form->setCarRequest($CarRequest);
    }

    public function save()
    {
        Gate::authorize('update-request', $this->carRequest);
        $this->form->update();

        return $this->redirectRoute('car.index');
    }

    public function render()
    {
        $users = User::select('name', 'id', 'badge_number', 'department_id')->where('department_id', Auth::user()->department_id)->get();

        return view('livewire.car-request.car-request-update', compact('users'));
    }

    private function calculateEndDate(): void
    {
        if (! $this->form->start) {
            $this->form->end = null;

            return;
        }

        $days = $this->date_long
            ? (int) Setting::get('vehicle_validity_days_long', 30)
            : (int) Setting::get('vehicle_validity_days', 7);

        $this->form->end = Carbon::parse($this->form->start)
            ->addDays($days)
            ->format('Y-m-d');
    }
}
