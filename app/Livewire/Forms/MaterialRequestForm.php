<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Jobs\MailRequestJob;
use App\Jobs\MailUserRequestNotifJob;
use App\Models\Document;
use App\Models\MaterialRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Throwable;

use function to_route;

final class MaterialRequestForm extends Form
{
    public ?MaterialRequest $materialRequest = null;

    #[Validate('required|array|min:1')]
    public array $materials = [];

    #[Validate('required|string')]
    public string $company = 'Somisy';

    #[Validate('nullable|exists:users,id')]
    public $person_out_id = '';

    #[Validate(['photos.*' => 'required|image|mimes:jpeg,png,jpg'])]
    public $photos = []; // Tableau pour stocker les fichiers

    public function addMaterial(): void
    {
        $this->materials[] = ['designation' => '', 'quantity' => 1, 'serial_number' => ''];
    }

    public function removeMaterial($index): void
    {
        unset($this->materials[$index]);
        $this->materials = array_values($this->materials); // Réindexer le tableau
    }

    public function setMaterialRequest(MaterialRequest $materialRequest): void
    {
        $this->materialRequest = $materialRequest;
        $this->fill($materialRequest);
    }

    public function store(): void
    {
        $this->validate([
            'materials' => 'required|array|min:1',
            'materials.*.designation' => 'required|string|min:3',
            'materials.*.quantity' => 'required|numeric|min:1',
            'materials.*.serial_number' => 'nullable|string|min:1',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg',
            'company' => 'required|string',
            'person_out_id' => 'nullable|exists:users,id',
        ]);
        if (empty($this->photos)) {
            flash()->error('Material request file is required');

            return;
        }
        try {
            DB::transaction(function () {

                $materialRequest = Auth::user()->material_requests()->create($this->only([
                    'company',

                ]));
                $this->person_out_id ? $materialRequest->person_out()->associate($this->person_out_id)->save() : null;
                $materialRequest->updateQuietly(['expire_at' => now()->addDay(7)]);
                if (! empty($this->photos)) {
                    foreach ($this->photos as $row) {
                        $filename = $row->hashName();
                        $chemin = $row->storeAs('material/document', $filename, 'public');
                        Document::create([
                            'material_request_id' => $materialRequest->id,
                            'chemin' => $chemin,
                        ]);
                    }
                }
                $materialRequest->material_request_items()->createMany($this->materials);
                $materialRequest->generateId('R');
                MailRequestJob::dispatch($materialRequest, 'Awaiting a material gate pass request to approve reference'.$materialRequest->reference);
				MailUserRequestNotifJob::dispatch($materialRequest, 'Your Material offsite gatepass request with reference '.$materialRequest->reference.' has been created. Please check the details.');
                $this->reset();
                flash('Material request created successfully');

                return to_route('material.index');
            });
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function update(): void
    {
        $this->validate([
            'materials' => 'required|array|min:1',
            'materials.*.designation' => 'required|string|min:3',
            'materials.*.quantity' => 'required|numeric|min:1',
            'materials.*.serial_number' => 'nullable|string|min:1',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg',
            'company' => 'required|string',
            'person_out_id' => 'nullable|exists:users,id',
        ]);

        DB::transaction(function () {
            $this->materialRequest->update($this->only([
                'company',

            ]));

            $this->person_out_id ? $this->materialRequest->person_out()->associate($this->person_out_id)->save() : null;
            if (! empty($this->photos)) {
                foreach ($this->photos as $key => $row) {
                    $filename = $row->hashName();
                    $chemin = $row->storeAs('material/document', $filename, 'public');
                    Document::create([
                        'material_request_id' => $this->materialRequest->id,
                        'chemin' => $chemin,
                    ]);
                }
            }
            if ($this->materials) {
                $this->updateMaterialRequestItems();
            }
            flash()->success('material request updated successfully');
        });
    }

    private function updateMaterialRequestItems(): void
    {
        $this->materialRequest->loadMissing('material_request_items');
        // Get the existing items as an associative array
        $existingItems = $this->materialRequest->material_request_items->keyBy('id');

        foreach ($this->materials as $material) {
            if (isset($material['id'])) {
                // Update existing item
                $existingItems[$material['id']]->update([
                    'designation' => $material['designation'],
                    'quantity' => $material['quantity'],
                ]);
            } else {
                // Create a new item
                $this->materialRequest->material_request_items()->create([
                    'designation' => $material['designation'],
                    'quantity' => $material['quantity'],
                ]);
            }
        }

        // Delete items that were removed
        $toDelete = $existingItems->keys()->diff(collect($this->materials)->pluck('id'));
        $this->materialRequest->material_request_items()->whereIn('id', $toDelete)->delete();
    }
}
