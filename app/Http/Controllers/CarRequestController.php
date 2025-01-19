<?php

namespace App\Http\Controllers;

use App\Helper\DeleteAction;
use App\Models\CarRequest;
use App\Http\Requests\StoreCarRequestRequest;
use App\Http\Requests\UpdateCarRequestRequest;

class CarRequestController extends Controller
{
    use DeleteAction;

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $carRequest)
    {
        $delete = CarRequest::findOrFail($carRequest);
        return $this->supp($delete);
    }
}
