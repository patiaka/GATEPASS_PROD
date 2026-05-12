<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use const false;

use App\Enum\RoleEnum;
use App\Events\RequestCreated;
use App\Models\CarRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class CarRequestForm extends Form
{
    public ?CarRequest $carRequest = null;

    #[Validate('nullable|array|min:1')]
    public ?array $drivers = [];

    #[Validate('nullable|array|min:1')]
    public ?array $passengers = [];

    #[Validate('required|string')]
    public string $somisy_car = '';

    #[Validate('required|string')]
    public string $resident = '';

    #[Validate('required|string')]
    public string $destination = '';

    #[Validate('nullable|string|in:Lv,Bus,Truck,Other')]
    public string $car_type = '';

    #[Validate('nullable|string')]
    public $car_number;

    #[Validate('nullable|string')]
    public $comment;

    #[Validate('required|date')]
    public $start;

    public $end;

    #[Validate('required|string')]
    public string $depart_at = '';

    #[Validate('required|string')]
    public string $arrive_at = '';

    #[Validate('required|string')]
    public string $reason = '';

    #[Validate('required|string')]
    public string $company = 'Somisy';

    public ?string $destination_other = null;

    public ?string $type_other = null;

    public function getShowDestinationField(): bool
    {
        return $this->destination === 'Other';
    }

    public function getShowVehicleField(): bool
    {
        if ($this->somisy_car === 'no_vehicle') {
            $this->drivers = [
                ['user_id' => ''],
            ];
            $this->car_type = '';
            $this->car_number = '';

            return false;
        }

        return $this->somisy_car !== 'no_vehicle';
    }

    public function add(string $type): void
    {
        if ($type === 'driver') {
            $this->drivers[] = ['user_id' => ''];
        } elseif ($type === 'passenger') {
            $this->passengers[] = ['user_id' => ''];
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
            'drivers' => 'required_unless:somisy_car,no_vehicle|array',
            'drivers.*.user_id' => 'nullable|integer|min:1|exists:users,id',
            'passengers' => 'required_if:somisy_car,no_vehicle|array|min:1',
            'passengers.*.user_id' => 'nullable|integer|min:1|exists:users,id',
            'somisy_car' => 'required|string',
            'resident' => 'required|string',
            'destination' => 'required|string|in:Paysan,Taba,A21,Other',
            'destination_other' => 'nullable|string',
            'comment' => 'nullable|string',
            'car_type' => 'required_unless:somisy_car,no_vehicle|string|in:Lv,Bus,Truck,Other',
            'type_other' => 'nullable|string',
            'car_number' => 'required_unless:somisy_car,no_vehicle|string',
            'start' => 'required|date|after_or_equal:today',
            'depart_at' => 'required|string',
            'arrive_at' => 'required|string',
            'reason' => 'required|string',
            'company' => 'required|string',
        ]);
        
        DB::transaction(function () {
            $this->destination = $this->getShowDestinationField() ? $this->destination_other : $this->destination;
            $this->car_type = $this->car_type === 'Other' ? $this->type_other : $this->car_type;

            $CarRequest = Auth::user()->car_requests()->create($this->only([
                'somisy_car',
                'resident',
                'destination',
                'car_type',
                'car_number',
                'start',
                'end',
                'depart_at',
                'arrive_at',
                'reason',
                'company',
                'comment',
            ]));

            if ($this->drivers and $this->somisy_car !== 'no_vehicle') {
                $CarRequest->car_drivers()->createMany($this->drivers);
            }

            if ($this->passengers and $this->somisy_car === 'no_vehicle') {
                $CarRequest->passengers()->createMany($this->passengers);
            }

            $CarRequest->generateId('VEH');
            $CarRequest->updateQuietly(['next_approver_role' => RoleEnum::HOD->value]);

            RequestCreated::dispatch($CarRequest);

            // MailRequestJob::dispatch($CarRequest, 'Awaiting a vehicle gate pass request to approve reference '.$CarRequest->reference);
			// MailUserRequestNotifJob::dispatch($CarRequest, 'Your vehicle offsite gatepass request with reference '.$CarRequest->reference.' has been created. Please check the details.');

            $this->reset();
            flash()->success('Car request submitted successfully');

            return redirect()->route('car.index');
        });
    }

    public function update(): void
    {
        $this->validate([
            'drivers' => 'required_unless:somisy_car,no_vehicle|array',
            'drivers.*.user_id' => 'nullable|integer|min:1|exists:users,id',
            'passengers' => 'required_if:somisy_car,no_vehicle|array|min:1',
            'passengers.*.user_id' => 'nullable|integer|min:1|exists:users,id',
            'somisy_car' => 'required|string',
            'resident' => 'required|string',
            'destination' => 'required|string|in:Paysan,Taba,A21,Other',
            'destination_other' => 'nullable|string',
            'comment' => 'nullable|string',
            'type_other' => 'nullable|string',
            'car_type' => 'required_unless:somisy_car,no_vehicle|string|in:Lv,Bus,Truck,Other',
            'car_number' => 'required_unless:somisy_car,no_vehicle|string',
            'start' => 'required|date|after_or_equal:today',
            'depart_at' => 'required|string',
            'arrive_at' => 'required|string',
            'reason' => 'required|string',
            'company' => 'required|string',
        ]);

        DB::transaction(function () {
            $this->destination = $this->getShowDestinationField() ? $this->destination_other : $this->destination;
            
            $this->car_type = $this->car_type === 'Other' ? $this->type_other : $this->car_type;
            
            $this->carRequest->update($this->only([
                'somisy_car',
                'resident',
                'destination',
                'car_type',
                'car_number',
                'start',
                'end',
                'depart_at',
                'arrive_at',
                'reason',
                'company',
                'comment',
            ]));

            if ($this->drivers and $this->somisy_car !== 'no_vehicle') {
                $this->updateRelation('car_drivers', 'car_drivers', $this->drivers);
            }

            if ($this->passengers and $this->somisy_car === 'no_vehicle') {
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
                    'user_id' => $row['user_id'],
                ]);
            } else {
                // Create new item
                $this->carRequest->$relationMethod()->create([
                    'user_id' => $row['user_id'],
                ]);
            }
        }

        // Delete items that were removed
        $itemIds = collect($items)->pluck('id');
        $toDelete = $existingItems->keys()->diff($itemIds);
        $this->carRequest->$relationMethod()->whereIn('id', $toDelete)->delete();
    }
}
