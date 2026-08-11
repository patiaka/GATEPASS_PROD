<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\MaterialRequestStatus;
use App\Models\CarRequest;
use App\Models\Recording;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

use function compact;

#[Title('Gate console')]
final class GateConsole extends Component
{
    #[Url]
    public string $search = '';

    public function clearSearch(): void
    {
        $this->search = '';
    }

    public function render()
    {
        $term = '%'.$this->search.'%';

        $results = CarRequest::query()
            ->where('status', MaterialRequestStatus::Approved)
            ->select('car_requests.*')
            ->addSelect(['last_action' => Recording::query()
                ->select('action')
                ->whereColumn('requestable_id', 'car_requests.id')
                ->where('requestable_type', CarRequest::class)
                ->orderByDesc('id')
                ->limit(1),
            ])
            ->when($this->search !== '', function ($q) use ($term) {
                $q->where(function ($w) use ($term) {
                    $w->where('reference', 'like', $term)
                        ->orWhere('car_number', 'like', $term)
                        ->orWhere('company', 'like', $term)
                        ->orWhereHas('car_drivers.user', fn ($u) => $u->where('name', 'like', $term));
                });
            })
            ->with([
                'car_drivers.user:id,name,contact',
                'user:id,name,department_id',
                'user.department:id,name',
            ])
            ->latest('car_requests.id')
            ->take(24)
            ->get();

        return view('livewire.gate-console', compact('results'));
    }
}
