<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Exports\RecordingExport;
use App\Helper\WithFilter;
use App\Models\CarRequest;
use App\Models\Recording;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

use function compact;

#[Title('Check Vehicle offsite request')]
final class CarRequestCheckIn extends Component
{
    use WithFilter;

    public function ResetFilter(): void
    {
        $this->reset('search', 'period');
        $this->resetPage();
    }

    public function export()
    {
        return (new RecordingExport($this->baseQuery(), 'car'))->download('recordings.xlsx');
    }

    public function baseQuery()
    {
        $query = Recording::with('user', 'requestable:id,company,reference,car_number,car_type', 'car_driver:id,name,department_id', 'car_driver.department:id,name')
            ->whereHasMorph('requestable', [CarRequest::class])
            ->when($this->search, function ($query) {
                $term = '%'.$this->search.'%';

                $query->where(function ($q) use ($term) {
                    $q->where('gate', 'like', $term)
                        ->orWhere('action', 'like', $term)
                        ->orWhere('fuel_level', 'like', $term)
                        ->orWhere('kilometers', 'like', $term)
                        ->orWhereHasMorph('requestable', [CarRequest::class], function ($sub) use ($term) {
                            $sub->where('reference', 'like', $term)
                                ->orWhere('car_number', 'like', $term)
                                ->orWhere('company', 'like', $term)
                                ->orWhere('car_type', 'like', $term);
                        })
                        ->orWhereHas('user', fn ($sub) => $sub->where('name', 'like', $term))
                        ->orWhereHas('car_driver', fn ($sub) => $sub->where('name', 'like', $term));
                });
            });

        $this->applyPeriod($query);

        return $query->latest('id');
    }

    #[Computed]
    public function rows()
    {
        return $this->baseQuery()->paginate();
    }

    public function render()
    {
        return view('livewire.car-request.car-request-check-in');
    }
}
