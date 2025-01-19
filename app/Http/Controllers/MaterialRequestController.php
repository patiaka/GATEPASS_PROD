<?php

namespace App\Http\Controllers;

use App\Helper\DeleteAction;
use App\Models\MaterialRequest;

class MaterialRequestController extends Controller
{
    use DeleteAction;
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
