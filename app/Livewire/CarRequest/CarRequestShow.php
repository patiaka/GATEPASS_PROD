<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Helper\ApproveAction;
use App\Models\CarRequest;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Browsershot\Browsershot;

#[Title('Show vehicle request')]
final class CarRequestShow extends Component
{
    use ApproveAction;

    public CarRequest $carRequest;

    public function mount(CarRequest $CarRequest)
    {
        $this->carRequest = $CarRequest;
        $this->carRequest->loadMissing(
            'user:id,name,email,department_id,badge_number',
            'user.department:id,name',
            'gmApproval.department:id,name',
            'hodApproval.department:id,name',
            'car_drivers',
            'passengers',
            'car_drivers.user:id,name,contact,badge_number',
            'passengers.user:id,name,contact,badge_number',
        );
    }

    /**
     * Duplication : ouvre le formulaire de création pré-rempli à partir de
     * cette demande (aucun enregistrement n'est créé tant que l'utilisateur
     * n'a pas soumis).
     */
    public function duplicate()
    {
        return $this->redirectRoute('car.create', ['from' => $this->carRequest->id]);
    }

    /**
     * Annulation (admin) : marque la demande comme Annulée.
     */
    public function cancel()
    {
        Gate::authorize('cancel-request', $this->carRequest);
        $this->carRequest->cancel();
        \App\Events\RequestCancelled::dispatch($this->carRequest->fresh());
        flash()->success('Request cancelled.');

        return $this->redirectRoute('car.index');
    }

    public function download_pdf(CarRequest $carRequest)
    {
        Gate::authorize('download-request', $carRequest);
        $html = view('car-request-download', compact('carRequest'))->render();

        $path = storage_path("app/request-{$carRequest->reference}.pdf");

        \App\Support\Pdf::make($html)->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
