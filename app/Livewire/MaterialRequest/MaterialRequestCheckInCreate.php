<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use App\Enum\MaterialRequestStatus;
use App\Models\MaterialRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Material Off site Check In/Out')]
final class MaterialRequestCheckInCreate extends Component
{
    #[Validate('required|string|in:Exit,Entry')]
    public string $action = '';

    #[Validate('required|string|in:Back,Front,Airport')]
    public string $gate = '';

    #[Url(as: 'request')]
    #[Validate('required|exists:material_requests,id')]
    public $material_request_id = '';

    public ?MaterialRequest $materialRequest = null;

    public ?string $last_movement = null;

    public function mount()
    {
        // Présélection depuis le bouton "Record" des listes (?request=ID)
        $id = $this->material_request_id ?: request('request');
        if ($id) {
            $this->material_request_id = (string) $id;
            $this->updatedMaterialRequestId($id);
        }
    }

    public function updatedMaterialRequestId($value)
    {
        $this->materialRequest = MaterialRequest::with(['material_request_items', 'user:id,name,email,department_id,poste', 'user.department:id,name', 'gmApproval.department:id,name', 'hodApproval.department:id,name', 'documents'])
            ->find($value);

        // Suggérer automatiquement le mouvement opposé au dernier enregistré
        $last = $this->materialRequest?->recordings()->latest('id')->first();
        $this->last_movement = $last
            ? "{$last->action} — {$last->gate} gate — {$last->created_at->format('Y-m-d H:i')}"
            : null;
        $this->action = $last?->action === 'Exit' ? 'Entry' : 'Exit';
    }

    public function recordSecurityCheck()
    {
        $this->validate();
        $item = MaterialRequest::findOrFail($this->material_request_id);
        // Vérifier expiration
        if ($item->isExpired()) {
            flash()->error('This request has expired.');

            return;
        }

        // Empêcher deux mouvements identiques consécutifs (pas 2 Exit ni 2 Entry de suite)
        $lastAction = $item->recordings()->latest('id')->value('action');
        if ($lastAction === $this->action) {
            flash()->error("This request was already recorded as '{$this->action}'. Please record the opposite movement first.");

            return;
        }

        $item->recordings()->create([
            'action' => $this->action,      // 'Entry' ou 'Exit'
            'gate' => $this->gate,
            // Un mouvement enregistré = passage validé (colonne NOT NULL en base)
            'decision' => 'Approved',
            'checked_at' => now(),
            'user_id' => Auth::user()->id,
        ]);
        $this->reset();
        flash()->success('Record security check added');
    }

    public function render()
    {
        $materialRequests = MaterialRequest::where('status', MaterialRequestStatus::Approved)->get();

        return view('livewire.material-request.material-request-check-in-create', compact('materialRequests'));
    }
}
