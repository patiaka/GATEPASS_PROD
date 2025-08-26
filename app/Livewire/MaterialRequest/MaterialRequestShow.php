<?php

namespace App\Livewire\MaterialRequest;

use Livewire\Component;
use App\Helper\ApproveAction;
use App\Models\MaterialRequest;
use Spatie\Browsershot\Browsershot;

class MaterialRequestShow extends Component
{
    use ApproveAction;
    public $MaterialRequest;

    public function mount(MaterialRequest $MaterialRequest)
    {
        $this->MaterialRequest = $MaterialRequest;

        $this->MaterialRequest->loadMissing('user:id,name,email,department_id', 'user.department:id,name', 'gmApproval.department:id,name', 'hodApproval.department:id,name', 'documents');
    }

    public function download_pdf(MaterialRequest $MaterialRequest)
    {
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
