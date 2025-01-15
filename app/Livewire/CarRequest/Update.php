<?php

namespace App\Livewire\CarRequest;

use App\Helper\RepeatInputAction;
use Livewire\Component;
use App\Models\CarRequest;
use Illuminate\Support\Facades\DB;

class Update extends Component
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
    public $carRequest;

    public function mount(CarRequest $car)
    {
        $this->carRequest = $car;
        $this->licence = $car->licence;
        $this->car_type = $car->car_type;
        $this->car_number = $car->car_number;
        $this->start = $car->start;
        $this->end = $car->end;
        $this->depart_at = $car->depart_at;
        $this->arrive_at = $car->arrive_at;
        $this->justification = $car->justification;
        $this->resident = $car->resident;
        $this->expatriate = $car->expatriate;
        $this->somisy_car = $car->somisy_car;
        $this->destination = $car->destination;

        $car->loadMissing('car_drivers', 'passengers');

        $car->car_drivers->pluck('name', 'contact')->each(function ($name, $contact) {
            $this->drivers[] = ['name' => $name, 'contact' => $contact];
        });

        $car->passengers->pluck('name', 'contact')->each(function ($name, $contact) {
            $this->passengers[] = ['name' => $name, 'contact' => $contact];
        });
    }


    private function updateRelation(string $relation, string $relationMethod, array $items): void
    {
        $this->carRequest->loadMissing('car_drivers', 'passengers');
        $existingItems = $this->carRequest->$relation->keyBy('id');

        foreach ($items as $row) {
            if (isset($row['id'])) {
                // Update existing item
                $existingItems[$row['id']]->update([
                    'name' => $row['name'],
                    'contact' => $row['contact'],
                ]);
            } else {
                // Create new item
                $this->carRequest->$relationMethod()->create([
                    'name' => $row['name'],
                    'contact' => $row['contact'],
                ]);
            }
        }

        // Delete items that were removed
        $itemIds = collect($items)->pluck('id');
        $toDelete = $existingItems->keys()->diff($itemIds);
        $this->carRequest->$relationMethod()->whereIn('id', $toDelete)->delete();
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
            $this->carRequest->update([
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
                $this->updateRelation('car_drivers', 'car_drivers', $this->drivers);
            }
            if ($this->passengers) {
                $this->updateRelation('passengers', 'passengers', $this->passengers);
            }

            flash('Car request update successfully');
        });

        return to_route('car.index');
    }
}
