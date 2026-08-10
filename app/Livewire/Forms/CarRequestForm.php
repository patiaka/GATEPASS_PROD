<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use const false;

use App\Enum\RoleEnum;
use App\Events\RequestCreated;
use App\Models\CarRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

use function filled;

final class CarRequestForm extends Form
{
    public ?CarRequest $carRequest = null;

    #[Validate('nullable|array|min:1')]
    public ?array $drivers = [];

    #[Validate('nullable|array|min:1')]
    public ?array $passengers = [];

    /** @var array<int, int> Multi-select : IDs des chauffeurs sélectionnés */
    public array $driver_ids = [];

    /** @var array<int, int> Multi-select : IDs des passagers sélectionnés */
    public array $passenger_ids = [];

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
            $this->driver_ids = [];
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
        $this->stripCarNumberPrefix();
    }

    public function setCarRequest(CarRequest $carRequest): void
    {
        $this->carRequest = $carRequest;
        $this->fill($carRequest);
        $this->stripCarNumberPrefix();

        // Préremplir les multi-selects depuis les relations existantes.
        // Les valeurs doivent être des chaînes pour correspondre aux checkboxes
        // du composant multi-select (sinon Livewire ne coche pas les cases).
        $carRequest->loadMissing('car_drivers', 'passengers');
        $this->driver_ids = $carRequest->car_drivers->pluck('user_id')->filter()->map(fn ($id) => (string) $id)->values()->all();
        $this->passenger_ids = $carRequest->passengers->pluck('user_id')->filter()->map(fn ($id) => (string) $id)->values()->all();
    }

    /**
     * Pré-remplit le formulaire de création à partir d'une demande existante
     * (duplication). Les dates ne sont pas copiées (à re-choisir) et aucune
     * donnée d'approbation/référence n'est reprise.
     */
    public function fillFromSource(CarRequest $source): void
    {
        $source->loadMissing('car_drivers', 'passengers');

        $this->somisy_car = $source->somisy_car ?? '';
        $this->resident = $source->resident ?? '';
        $this->destination = $source->destination ?? '';
        $this->car_type = $source->car_type ?? '';
        $this->car_number = $source->car_number;
        $this->depart_at = $source->depart_at ?? '';
        $this->arrive_at = $source->arrive_at ?? '';
        $this->reason = $source->reason ?? '';
        $this->company = $source->company ?? 'Somisy';
        $this->comment = $source->comment;

        $this->stripCarNumberPrefix();

        $this->driver_ids = $source->car_drivers->pluck('user_id')->filter()->map(fn ($id) => (string) $id)->values()->all();
        $this->passenger_ids = $source->passengers->pluck('user_id')->filter()->map(fn ($id) => (string) $id)->values()->all();
    }

    public function store(): void
    {
        $this->validate([
            'driver_ids' => 'required_unless:somisy_car,no_vehicle|array',
            'driver_ids.*' => 'integer|exists:users,id',
            'passenger_ids' => 'required_if:somisy_car,no_vehicle|array',
            'passenger_ids.*' => 'integer|exists:users,id',
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

        // Multi-select -> structure attendue par les relations
        $this->drivers = $this->mapIdsToRows($this->driver_ids);
        $this->passengers = $this->mapIdsToRows($this->passenger_ids);

        DB::transaction(function () {
            $this->destination = $this->getShowDestinationField() ? $this->destination_other : $this->destination;
            $this->car_type = $this->car_type === 'Other' ? $this->type_other : $this->car_type;
            $this->normalizeCarNumber();

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
            'driver_ids' => 'required_unless:somisy_car,no_vehicle|array',
            'driver_ids.*' => 'integer|exists:users,id',
            'passenger_ids' => 'required_if:somisy_car,no_vehicle|array',
            'passenger_ids.*' => 'integer|exists:users,id',
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
            $this->normalizeCarNumber();

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

            if ($this->somisy_car !== 'no_vehicle') {
                $this->syncRelationFromIds('car_drivers', $this->driver_ids);
            } else {
                $this->syncRelationFromIds('passengers', $this->passenger_ids);
            }

            flash()->success('Car request updated successfully');
        });
    }

    /**
     * Le champ saisi ne contient que le numéro ; on stocke toujours avec le préfixe LV-.
     */
    private function normalizeCarNumber(): void
    {
        // Préfixe LV- uniquement pour les véhicules légers (type Lv).
        if ($this->somisy_car !== 'no_vehicle' && $this->car_type === 'Lv' && filled($this->car_number)) {
            $number = trim((string) $this->car_number);
            $this->car_number = Str::startsWith($number, 'LV-') ? $number : 'LV-'.$number;
        }
    }

    /**
     * À l'édition, on retire le préfixe LV- pour n'afficher que le numéro dans le champ.
     */
    private function stripCarNumberPrefix(): void
    {
        if (filled($this->car_number)) {
            $this->car_number = Str::after((string) $this->car_number, 'LV-');
        }
    }

    /**
     * Transforme une liste d'IDs utilisateurs en lignes [['user_id' => id], ...].
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, array{user_id: int}>
     */
    private function mapIdsToRows(array $ids): array
    {
        return collect($ids)
            ->filter()
            ->unique()
            ->map(fn ($id) => ['user_id' => (int) $id])
            ->values()
            ->all();
    }

    /**
     * Remplace le contenu d'une relation (car_drivers / passengers) par les IDs choisis.
     *
     * @param  array<int, int|string>  $ids
     */
    private function syncRelationFromIds(string $relationMethod, array $ids): void
    {
        $this->carRequest->$relationMethod()->delete();
        $rows = $this->mapIdsToRows($ids);
        if ($rows) {
            $this->carRequest->$relationMethod()->createMany($rows);
        }
    }
}
