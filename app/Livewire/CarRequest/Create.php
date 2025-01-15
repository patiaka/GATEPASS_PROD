<?php

namespace App\Livewire\CarRequest;

use Auth;
use Livewire\Component;
use App\Models\CarRequest;
use App\Helper\RepeatInputAction;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    use RepeatInputAction;
    public string $expatriate = "";
    public string $resident = "";
    public string $somisy_car = "";
    public string $licence = "";
    public string $destination = "";
    public string $car_type = "";
    public string $car_number = "";
    public string $start = "";
    public string $end = "";
    public string $depart_at = "";
    public string $arrive_at = "";
    public string $justification = "";

    public function mount()
    {
        $this->drivers = [
            ['name' => '', 'contact' => ''], // Un élément initial
        ];

        $this->passengers = [
            ['name' => '', 'contact' => ''], // Un élément initial
        ];
    }

    public function save()
    {
        $this->validate([
            'drivers' => 'required|array|min:1',
            'drivers.*.name' => 'required|string|min:3',
            'drivers.*.contact' => 'required|string|min:1',
            'passengers' => 'nullable|array|min:1',
            'passengers.*.name' => 'nullable|string|min:3',
            'passengers.*.contact' => 'nullable|string|min:1',
            'somisy_car' => 'required|string',
            'expatriate' => 'required|string',
            'resident' => 'required|string',
            'licence' => 'required|string|in:Mali DL,Foreign DL,Intl Permit',
            'destination' => 'required|string',
            'car_type' => 'required|string|in:Lv,Bus,Truck',
            'car_number' => 'required',
            'start' => 'required',
            'end' => 'required',
            'depart_at' => 'required|string',
            'arrive_at' => 'required|string',
            'justification' => 'required',
        ]);

        DB::transaction(function () {
            $CarRequest = CarRequest::create([
                'user_id' => Auth::user()->id,
                'somisy_car' => $this->somisy_car,
                'resident' => $this->resident,
                'expatriate' => $this->expatriate,
                'licence' => $this->licence,
                'destination' => $this->destination,
                'car_type' => $this->car_type,
                'car_number' => $this->car_number,
                'start' => $this->start,
                'end' => $this->end,
                'depart_at' => $this->depart_at,
                'arrive_at' => $this->arrive_at,
                'justification' => $this->justification,
            ]);

            if ($this->drivers) {
                $CarRequest->car_drivers()->createMany($this->drivers);
            }
            if ($this->passengers) {
                $CarRequest->passengers()->createMany($this->passengers);
            }

            $CarRequest->generateId('CR');
            flash('Car request created successfully');
        });
        return to_route('car.index');
    }
}
