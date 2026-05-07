<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use App\Enum\MaterialRequestStatus;
use App\Models\MaterialRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Material Off site Check In/Out')]
final class MaterialRequestCheckInCreate extends Component
{
    #[Validate('required|string|in:Approved,Rejected')]
    public string $decision = '';

    #[Validate('required|string|in:Exit,Entry')]
    public string $action = '';

    #[Validate('required|string|in:Back,Front')]
    public string $gate = '';

    #[Validate('required|exists:material_requests,id')]
    public $material_request_id = '';

    public ?MaterialRequest $materialRequest = null;

    public function updatedMaterialRequestId($value)
    {
        $this->materialRequest = MaterialRequest::with(['material_request_items', 'user:id,name,email,department_id,poste', 'user.department:id,name', 'gmApproval.department:id,name', 'hodApproval.department:id,name', 'documents'])
            ->find($value);
    }

    public function recordSecurityCheck()
    {

        $this->validate();
        $item = MaterialRequest::findOrFail($this->material_request_id);
        // Vérifier expiration
        if ($item->isExpired()) {
            flash()->success('request expired');

            return;
        }

        $item->recordings()->create([
            'action' => $this->action,      // 'entry' ou 'exit'
            'gate' => $this->gate,
            'decision' => $this->decision,    // 'validated' ou 'rejected'
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
