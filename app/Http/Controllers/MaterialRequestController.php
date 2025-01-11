<?php

namespace App\Http\Controllers;

use App\Helper\DeleteAction;
use App\Models\MaterialRequestItem;
use App\Http\Requests\StoreMaterialRequestItemRequest;
use App\Http\Requests\UpdateMaterialRequestItemRequest;
use App\Models\MaterialRequest;

class MaterialRequestController extends Controller
{
    use DeleteAction;
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $materialRequest)
    {
        $delete = MaterialRequestItem::findOrFail($materialRequest);

        return $this->supp($delete);
    }
}
