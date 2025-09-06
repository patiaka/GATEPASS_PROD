<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use Livewire\Component;
use App\Models\CarRequest;
use App\Helper\ApproveAction;
use Livewire\Attributes\Title;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Gate;

#[Title('Show vehicle request')]
final class CarRequestShow extends Component
{
    use ApproveAction;

    public CarRequest $carRequest;

    public function mount(CarRequest $CarRequest)
    {
        $this->carRequest = $CarRequest;

        $this->carRequest->loadMissing('user:id,name,email,department_id', 'user.department:id,name', 'gmApproval.department:id,name', 'hodApproval.department:id,name', 'car_drivers', 'passengers');
    }

    public function download_pdf(CarRequest $carRequest)
    {
        Gate::authorize('download-request', $carRequest);
        $html = view('car-request-download', compact('carRequest'))->render();

        $path = storage_path("app/request-{$carRequest->reference}.pdf");

        Browsershot::html($html)
            ->margins(10, 10, 10, 10)
            ->format('A4')
            ->showBackground()
            ->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
