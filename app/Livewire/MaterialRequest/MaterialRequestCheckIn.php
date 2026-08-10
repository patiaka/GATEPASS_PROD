<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use App\Exports\RecordingExport;
use App\Helper\WithFilter;
use App\Models\MaterialRequest;
use App\Models\Recording;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Check material request')]
final class MaterialRequestCheckIn extends Component
{
    use WithFilter;

    public function ResetFilter(): void
    {
        $this->reset('search', 'period', 'debut', 'fin');
        $this->resetPage();
    }

    public function export()
    {
        return (new RecordingExport($this->baseQuery(), 'material'))->download('material-recordings.xlsx');
    }

    public function baseQuery()
    {
        $query = Recording::with('user', 'requestable:id,company,reference,user_id,person_out_id,person_out_name', 'requestable.user.department:id,name', 'requestable.person_out:id,name')
            ->whereHasMorph('requestable', [MaterialRequest::class])
            ->when($this->search, function ($query) {
                $term = '%'.$this->search.'%';

                $query->where(function ($q) use ($term) {
                    $q->where('gate', 'like', $term)
                        ->orWhere('action', 'like', $term)
                        ->orWhereHasMorph('requestable', [MaterialRequest::class], function ($sub) use ($term) {
                            $sub->where('reference', 'like', $term)
                                ->orWhere('company', 'like', $term)
                                ->orWhere('person_out_name', 'like', $term)
                                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $term))
                                ->orWhereHas('person_out', fn ($p) => $p->where('name', 'like', $term));
                        })
                        ->orWhereHas('user', fn ($sub) => $sub->where('name', 'like', $term));
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
        return view('livewire.material-request.material-request-check-in');
    }
}
