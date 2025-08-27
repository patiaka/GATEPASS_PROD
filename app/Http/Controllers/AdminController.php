<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enum\MaterialRequestStatus;
use App\Models\CarRequest;
use App\Models\Compagnie;
use App\Models\Department;
use App\Models\MaterialRequest;
use App\Models\User;

final class AdminController extends Controller
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
        $MaterialPending = $queryMaterial->where('status', MaterialRequestStatus::Pending->value)->count();
        $MaterialApproved = $queryMaterial->where('status', MaterialRequestStatus::Approved->value)->count();
        $MaterialRejected = $queryMaterial->where('status', MaterialRequestStatus::Rejected->value)->count();
        $MaterialProgress = $queryMaterial->where('status', MaterialRequestStatus::Progress->value)->count();
        $queryCar = CarRequest::query();
        $CarPending = $queryCar->where('status', MaterialRequestStatus::Pending->value)->count();
        $CarApproved = $queryCar->where('status', MaterialRequestStatus::Approved->value)->count();
        $CarRejected = $queryCar->where('status', MaterialRequestStatus::Rejected->value)->count();
        $CarProgress = $queryCar->where('status', MaterialRequestStatus::Progress->value)->count();

        return view('dashboard', compact('CountUser', 'CountDepartment', 'CountCompagnie', 'MaterialPending', 'MaterialApproved', 'MaterialRejected', 'MaterialProgress', 'CarPending', 'CarApproved', 'CarRejected', 'CarProgress'));
    }
}
