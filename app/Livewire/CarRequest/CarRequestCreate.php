<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use const false;

use App\Helper\RepeatInputAction;
use App\Livewire\Forms\CarRequestForm;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Vehicle Off Site Form')]
final class CarRequestCreate extends Component
{
    use RepeatInputAction;

    public CarRequestForm $form;

    public bool $date_long = false;

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

    public function mount()
    {
        $this->form->drivers = [
            ['user_id' => ''], // Un élément initial
        ];

        $this->form->passengers = [
            ['user_id' => ''], // Un élément initial
        ];
    }

    public function save()
    {
        $this->form->store();
    }

    public function render()
    {
        $users = User::select('name', 'id', 'badge_number', 'department_id')->where('department_id', Auth::user()->department_id)->get();

        return view('livewire.car-request.car-request-create', compact('users'));
    }

    private function calculateEndDate(): void
    {
        if (! $this->form->start) {
            $this->form->end = null;

            return;
        }

        $days = $this->date_long ? 30 : 7;

        $this->form->end = Carbon::parse($this->form->start)
            ->addDays($days)
            ->format('Y-m-d');
    }
}
