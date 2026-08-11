<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\MaterialRequestStatus;
use App\Models\CarRequest;
use App\Models\Recording;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

use function compact;

#[Title('Gate console')]
final class GateConsole extends Component
{
    /** Porte de la guérite (mémorisée dans l'URL). */
    #[Url]
    public string $gate = 'Front';

    #[Url]
    public string $search = '';

    public function setGate(string $gate): void
    {
        if (in_array($gate, ['Front', 'Back', 'Airport'], true)) {
            $this->gate = $gate;
        }
    }

    public function clearSearch(): void
    {
        $this->search = '';
    }

    /** Enregistre le mouvement OPPOSÉ au dernier (Sortie <-> Entrée), à la porte choisie. */
    public function record(int $id): void
    {
        $item = CarRequest::query()->where('status', MaterialRequestStatus::Approved)->find($id);

        if (! $item) {
            flash()->error(__('This request is not available.'));

            return;
        }

        if ($item->isExpired()) {
            flash()->error(__('This request has expired.'));

            return;
        }

        $lastAction = $item->recordings()->latest('id')->value('action');
        $action = $lastAction === 'Exit' ? 'Entry' : 'Exit';

        $item->recordings()->create([
            'action' => $action,
            'gate' => $this->gate,
            'decision' => 'Approved',
            'checked_at' => now(),
            'user_id' => Auth::id(),
        ]);

        flash()->success(__(':action recorded for :ref (:gate gate)', [
            'action' => __($action),
            'ref' => $item->reference,
            'gate' => $this->gate,
        ]));
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
            ->with('car_drivers.user:id,name,contact')
            ->latest('car_requests.id')
            ->take(24)
            ->get();

        return view('livewire.gate-console', compact('results'));
    }
}
