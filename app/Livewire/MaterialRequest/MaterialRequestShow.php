<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use Livewire\Component;
use App\Models\Document;
use App\Helper\DeleteAction;
use App\Helper\ApproveAction;
use Livewire\Attributes\Title;
use App\Models\MaterialRequest;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Gate;

#[Title('Show material request')]
final class MaterialRequestShow extends Component
{
    use ApproveAction, DeleteAction;

    public $MaterialRequest;

    public function mount(MaterialRequest $MaterialRequest)
    {
        $this->MaterialRequest = $MaterialRequest;

        $this->MaterialRequest->loadMissing('user:id,name,email,department_id', 'user.department:id,name', 'gmApproval.department:id,name', 'hodApproval.department:id,name', 'documents');
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
        $html = view('material-request-download', compact('MaterialRequest'))->render();

        $path = storage_path("app/request-{$MaterialRequest->reference}.pdf");

        Browsershot::html($html)
            ->margins(10, 10, 10, 10)
            ->format('A4')
            ->showBackground()
            ->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
