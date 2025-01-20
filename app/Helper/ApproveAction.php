<?php

declare(strict_types=1);

namespace App\Helper;

use Auth;
use App\Models\CarRequest;
use App\Jobs\MailRequestJob;
use App\Models\MaterialRequest;
use Illuminate\Validation\Rule;
use App\Enum\MaterialRequestStatus;
use Gate;

trait ApproveAction
{

    public string $hod_comment = "";
    public string $gm_comment = "";
    public string $status = "";

    private function dispatchApprovalMail($request, string $role)
    {
        $action = $this->status === 'Approved' ? 'validé' : 'rejeté';
        $message = "le $role a $action votre request reference " . $request->reference;
        MailRequestJob::dispatch($request, $message);
    }

    public function approveByHod(int $id, string $type)
    {
        Gate::authorize('action-approved-request', Auth::user());
        $this->validate([
            'hod_comment' => 'required|string|min:3',
            'status' => ['required', Rule::in(['Approved', 'Rejected'])],
        ]);
        if ($type === 'material') {
            $request =  MaterialRequest::findOrFail($id);
            $request->update([
                'hod_approval_date' => now(),
                'hod_comment' => $this->hod_comment,
                'hod_approval_id' => Auth::user()->id,
                'status' => $this->status === 'Approved' ?  MaterialRequestStatus::Progress->value : MaterialRequestStatus::Rejected->value
            ]);
            $this->dispatchApprovalMail($request, 'hod');
            flash('Material request approved successfully');
            return to_route('material.index');
        } elseif ($type === 'car') {

            $request =  CarRequest::findOrFail($id);
            $request->update([
                'hod_approval_date' => now(),
                'hod_comment' => $this->hod_comment,
                'hod_approval_id' => Auth::user()->id,
                'status' => $this->status === 'Approved' ?  MaterialRequestStatus::Progress->value : MaterialRequestStatus::Rejected->value
            ]);
            $this->dispatchApprovalMail($request, 'hod');
            flash('Car request approved successfully');
            return to_route('car.index');
        }
    }

    public function approveByGm(int $id, string $type)
    {
        Gate::authorize('action-approved-request', Auth::user());
        $this->validate([
            'gm_comment' => 'required|string|min:3',
            'status' => ['required', Rule::in(['Approved', 'Rejected'])],
        ]);
        if ($type === 'material') {
            $request =  MaterialRequest::findOrFail($id);
            $request->update([
                'gm_comment' => $this->gm_comment,
                'gm_approval_date' => now(),
                'gm_approval_id' => Auth::user()->id,
                'status' => $this->status
            ]);
            $this->dispatchApprovalMail($request, 'gm');
            flash('Material request approved successfully');
            return to_route('material.index');
        } elseif ($type === 'car') {
            $request =  CarRequest::findOrFail($id);
            $request->update([
                'gm_comment' => $this->gm_comment,
                'gm_approval_date' => now(),
                'gm_approval_id' => Auth::user()->id,
                'status' => $this->status
            ]);
            $this->dispatchApprovalMail($request, 'gm');
            flash('Car request approved successfully');
            return to_route('car.index');
        }
    }
}
