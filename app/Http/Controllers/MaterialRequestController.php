<?php

namespace App\Http\Controllers;

use App\Helper\DeleteAction;
use App\Models\MaterialRequest;

class MaterialRequestController extends Controller
{
    use DeleteAction;

    public function print(int $materialRequest)
    {
        $materialRequest = MaterialRequest::with('user', 'hodApproval', 'gmApproval', 'material_request_items')->findOrFail($materialRequest);
        $materialRequest->loadMissing('hodApproval.compagnie', 'gmApproval.compagnie', 'hodApproval.department', 'gmApproval.department');
        return view('print_material', compact('materialRequest'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $materialRequest)
    {
        $delete = MaterialRequest::findOrFail($materialRequest);
        $delete->loadMissing('documents');
        if ($delete->documents) {
            foreach ($delete->loadMissing('documents')->documents as $row) {
                $this->file_delete($row);
            }
            $delete->documents->delete();
        }
        return $this->supp($delete);
    }
}
