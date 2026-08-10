<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Enum\MaterialRequestStatus;
use App\Enum\RoleEnum;
use App\Helper\ApproveAction;
use App\Models\CarRequest;
use Illuminate\Support\Facades\Auth;
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
     * Duplique la demande en une nouvelle demande "Pending" appartenant à
     * l'utilisateur courant, puis redirige vers son édition.
     */
    public function duplicate()
    {
        $original = $this->carRequest->loadMissing('car_drivers', 'passengers');

        $clone = $original->replicate([
            'reference', 'status', 'expire_at',
            'hod_approval_id', 'hod_comment', 'hod_approval_date',
            'director_approval_id', 'director_comment', 'director_approval_date',
            'gm_approval_id', 'gm_comment', 'gm_approval_date',
            'next_approver_role',
        ]);

        $clone->user_id = Auth::id();
        $clone->status = MaterialRequestStatus::Pending->value;
        $clone->reference = null;
        $clone->save();

        $clone->generateId('VEH');
        $clone->updateQuietly(['next_approver_role' => RoleEnum::HOD->value]);

        foreach ($original->car_drivers as $driver) {
            $clone->car_drivers()->create(['user_id' => $driver->user_id]);
        }
        foreach ($original->passengers as $passenger) {
            $clone->passengers()->create(['user_id' => $passenger->user_id]);
        }

        flash()->success('Request duplicated — you can now review and submit the copy.');

        return $this->redirectRoute('car.edit', ['CarRequest' => $clone]);
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
