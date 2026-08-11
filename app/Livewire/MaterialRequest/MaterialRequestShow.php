<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use App\Helper\ApproveAction;
use App\Helper\DeleteAction;
use App\Models\Document;
use App\Models\MaterialRequest;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Browsershot\Browsershot;

#[Title('Show material request')]
final class MaterialRequestShow extends Component
{
    use ApproveAction, DeleteAction;

    public $MaterialRequest;

    public function mount(MaterialRequest $MaterialRequest)
    {
        $this->MaterialRequest = $MaterialRequest;

        $this->MaterialRequest->loadMissing('user:id,name,email,department_id,poste', 'user.department:id,name', 'gmApproval.department:id,name', 'hodApproval.department:id,name', 'documents', 'person_out:id,name,badge_number');
    }

    /**
     * Duplication : ouvre le formulaire de création pré-rempli à partir de cette
     * demande (rien n'est enregistré tant que l'utilisateur n'a pas soumis).
     */
    public function duplicate()
    {
        return $this->redirectRoute('material.create', ['from' => $this->MaterialRequest->id]);
    }

    /**
     * Annulation (admin) : marque la demande comme Annulée.
     */
    public function cancel()
    {
        Gate::authorize('cancel-request', $this->MaterialRequest);
        $this->MaterialRequest->cancel();
        \App\Events\RequestCancelled::dispatch($this->MaterialRequest->fresh());
        flash()->success('Request cancelled.');

        return $this->redirectRoute('material.index');
    }

    public function delete(int $id): void
    {
        $row = Document::find($id);

        if (! $row) {
            flash()->error('Document not found.');

            return;
        }

        $this->file_delete($row);
        $row->delete();
        flash()->success('Document deleted with success');
    }

    public function download_pdf(MaterialRequest $MaterialRequest)
    {
        Gate::authorize('download-request', $MaterialRequest);
        $MaterialRequest->loadMissing('hodApproval', 'gmApproval');

        $html = view('material-request-download', compact('MaterialRequest'))->render();

        $path = storage_path("app/request-{$MaterialRequest->reference}.pdf");

        Browsershot::html($html)
            ->noSandbox()
            ->timeout(120)
            ->margins(10, 10, 10, 10)
            ->format('A4')
            ->showBackground()
            ->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
