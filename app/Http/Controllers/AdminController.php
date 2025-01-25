<?php

namespace App\Http\Controllers;

use App\Enum\MaterialRequestStatus;
use App\Models\CarRequest;
use App\Models\Compagnie;
use App\Models\User;
use App\Models\Department;
use App\Models\MaterialRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $CountUser = User::count();
        $CountDepartment = Department::count();
        $CountCompagnie = Compagnie::count();
        $queryMaterial = MaterialRequest::query();
        $MaterialPending = $queryMaterial->where('status', MaterialRequestStatus::Pending)->count();
        $MaterialApproved = $queryMaterial->where('status', MaterialRequestStatus::Approved)->count();
        $MaterialRejected = $queryMaterial->where('status', MaterialRequestStatus::Rejected)->count();
        $MaterialProgress = $queryMaterial->where('status', MaterialRequestStatus::Progress)->count();
        $queryCar = CarRequest::query();
        $CarPending = $queryCar->where('status', MaterialRequestStatus::Pending)->count();
        $CarApproved = $queryCar->where('status', MaterialRequestStatus::Approved)->count();
        $CarRejected = $queryCar->where('status', MaterialRequestStatus::Rejected)->count();
        $CarProgress = $queryCar->where('status', MaterialRequestStatus::Progress)->count();
        return view('dashboard', compact('CountUser', 'CountDepartment', 'CountCompagnie', 'MaterialPending', 'MaterialApproved', 'MaterialRejected', 'MaterialProgress', 'CarPending', 'CarApproved', 'CarRejected', 'CarProgress'));
    }
}
