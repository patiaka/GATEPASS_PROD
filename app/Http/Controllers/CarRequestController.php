<?php

namespace App\Http\Controllers;

use App\Helper\DeleteAction;
use App\Models\CarRequest;
use App\Http\Requests\StoreCarRequestRequest;
use App\Http\Requests\UpdateCarRequestRequest;

class CarRequestController extends Controller
{
    use DeleteAction;

    public function print(int $carRequest)
    {
        $carRequest = CarRequest::with('user', 'user.department', 'hodApproval', 'gmApproval')->findOrFail($carRequest);
        $carRequest->loadMissing('car_drivers', 'passengers');
        return view('print_car', compact('carRequest'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $carRequest)
    {
        $delete = CarRequest::findOrFail($carRequest);
        return $this->supp($delete);
    }
}
