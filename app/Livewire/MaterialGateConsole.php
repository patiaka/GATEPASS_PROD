<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\MaterialRequestStatus;
use App\Models\MaterialRequest;
use App\Models\Recording;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

use function compact;

#[Title('Material gate console')]
final class MaterialGateConsole extends Component
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

        $results = MaterialRequest::query()
            ->where('status', MaterialRequestStatus::Approved)
            ->select('material_requests.*')
            ->addSelect(['last_action' => Recording::query()
                ->select('action')
                ->whereColumn('requestable_id', 'material_requests.id')
                ->where('requestable_type', MaterialRequest::class)
                ->orderByDesc('id')
                ->limit(1),
            ])
            ->when($this->search !== '', function ($q) use ($term) {
                $q->where(function ($w) use ($term) {
                    $w->where('reference', 'like', $term)
                        ->orWhere('company', 'like', $term)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $term));
                });
            })
            ->with([
                'user:id,name,department_id',
                'user.department:id,name',
                'person_out:id,name',
            ])
            ->latest('material_requests.id')
            ->take(24)
            ->get();

        return view('livewire.material-gate-console', compact('results'));
    }
}
