<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Jobs\MailRequestJob;
use App\Models\CarRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;


final class CarRequestForm extends Form
{
    public ?CarRequest $carRequest = null;

    #[Validate('required|array|min:1')]
    public array $drivers = [];

    #[Validate('required|array|min:1')]
    public ?array $passengers = [];

    #[Validate('required|string')]
    public string $somisy_car = '';

    #[Validate('required|string|in:Yes,No,Escort')]
    public string $expatriate = '';

    #[Validate('required|string')]
    public string $resident = '';

    #[Validate('required|string|in:Mali DL,Foreign DL,Intl Permit')]
    public string $licence = '';

    #[Validate('required|string')]
    public string $destination = '';

    #[Validate('required|string|in:Lv,Bus,Truck')]
    public string $car_type = '';

    #[Validate('required')]
    public $car_number;

    #[Validate('required|date')]
    public $start;

    #[Validate('required|date')]
    public $end;

    #[Validate('required|string')]
    public string $depart_at = '';

    #[Validate('required|string')]
    public string $arrive_at = '';

    #[Validate('required|string')]
    public string $reason = '';

    #[Validate('required|string')]
    public string $route = '';

    #[Validate('required|string')]
    public string $company = '';

    public function add(string $type): void
    {
        if ($type === 'driver') {
            $this->drivers[] = ['name' => '', 'contact' => ''];
        } elseif ($type === 'passenger') {
            $this->passengers[] = ['name' => '', 'contact' => ''];
        }
    }

    public function remove(string $type, int $index): void
    {
        if ($type === 'driver') {
            unset($this->drivers[$index]);
            $this->drivers = array_values($this->drivers); // Réindexer le tableau
        } elseif ($type === 'passenger') {
            unset($this->passengers[$index]);
            $this->passengers = array_values($this->passengers); // Réindexer le tableau
        }
    }

    public function mount(CarRequest $carRequest): void
    {
        $this->carRequest = $carRequest;
        $this->fill($carRequest);
    }

    public function setCarRequest(CarRequest $carRequest): void
    {
        $this->carRequest = $carRequest;
        $this->fill($carRequest);
    }

    public function store(): void
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
            'reason' => 'required|string',
            'route' => 'required|string',
            'company' => 'required|string',
        ]);

        DB::transaction(function () {

            $CarRequest = Auth::user()->car_requests()->create($this->only([
                'somisy_car',
                'expatriate',
                'resident',
                'licence',
                'destination',
                'car_type',
                'car_number',
                'start',
                'end',
                'depart_at',
                'arrive_at',
                'reason',
                'route',
                'company',
            ]));
            if ($this->drivers) {
                $CarRequest->car_drivers()->createMany($this->drivers);
            }
            if ($this->passengers) {
                $CarRequest->passengers()->createMany($this->passengers);
            }

            $CarRequest->generateId('CR');
            MailRequestJob::dispatch($CarRequest, 'Awaiting a vehicle gate pass request to approve reference '.$CarRequest->reference);

            $this->reset();
            flash()->success('Car request submitted successfully');
            to_route('car.index');
        });
    }

    public function update(): void
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
            'reason' => 'required|string',
            'route' => 'required|string',
            'company' => 'required|string',
        ]);

        DB::transaction(function () {
            $this->carRequest->update($this->only([
                'somisy_car',
                'expatriate',
                'resident',
                'licence',
                'destination',
                'car_type',
                'car_number',
                'start',
                'end',
                'depart_at',
                'arrive_at',
                'reason',
                'route',
                'company',
            ]));

            if ($this->drivers) {
                $this->updateRelation('car_drivers', 'car_drivers', $this->drivers);
            }
            if ($this->passengers) {
                $this->updateRelation('passengers', 'passengers', $this->passengers);
            }
            flash()->success('Car request updated successfully');
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
}
