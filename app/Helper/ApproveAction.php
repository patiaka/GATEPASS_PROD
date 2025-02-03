<?php

declare(strict_types=1);

namespace App\Helper;

use Auth;
use Gate;
use Route;
use App\Models\CarRequest;
use App\Jobs\MailRequestJob;
use Illuminate\Support\Carbon;
use App\Models\MaterialRequest;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use App\Enum\MaterialRequestStatus;
use Illuminate\Database\Eloquent\Collection;

trait ApproveAction
{

    public string $hod_comment = "";
    public string $gm_comment = "";
    public string $status = "";

    private function dispatchApprovalMail($request, string $role)
    {
        $action = $this->status === 'Approved' ? 'valided' : 'rejected';
        $message = "The $role request reference $request->reference has been $action";
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
            MailRequestJob::dispatch($request, 'Awaiting a material gate pass request to approve reference ' . $request->reference);
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
            MailRequestJob::dispatch($request, 'Awaiting a vehicle gate pass request to approve reference ' . $request->reference);
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
                'status' => $this->status,
                'expire_at' =>  Carbon::now()->addDays(7),
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
                'status' => $this->status,
                'expire_at' =>  Carbon::now()->addDays(7),
            ]);
            $this->dispatchApprovalMail($request, 'gm');
            flash('Car request approved successfully');
            return to_route('car.index');
        }
    }

    private function dispatchApprovalMails(Collection $items, string $role, string  $action): void
    {
        $items->each(function ($item) use ($role, $action) {
            $message = "le $role a $action votre request reference " . $item->reference;
            MailRequestJob::dispatch($item, $message);
        });
    }

    public function bulkAction(string $action, string $type): void
    {
        Gate::authorize('action-approved-request', Auth::user());

        if ($type === 'material') {
            $query = MaterialRequest::query()->whereIn('id', $this->selectedRows);
        } elseif ($type === 'car') {
            $query = CarRequest::query()->whereIn('id', $this->selectedRows);
        }
        if ($action === 'reject') {
            if (Auth::user()->isHod()) {
                $query->where('status', MaterialRequestStatus::Pending)->update([
                    'status' => MaterialRequestStatus::Rejected,
                    'hod_approval_id' => Auth::user()->id,
                ]);
                $this->dispatchApprovalMails($query->get(), 'hod', 'rejeté');
            } elseif (Auth::user()->isGm()) {
                $query->where('status', MaterialRequestStatus::Progress)->update([
                    'status' => MaterialRequestStatus::Rejected,
                    'gm_approval_id' => Auth::user()->id,
                ]);
                $this->dispatchApprovalMails($query->get(), 'gm', 'rejeté');
            }
        } elseif ($action === 'approve') {
            if (Auth::user()->isHod()) {
                $query->where('status', MaterialRequestStatus::Pending)->update([
                    'status' => MaterialRequestStatus::Progress,
                    'hod_approval_id' => Auth::user()->id,
                ]);
                $this->dispatchApprovalMails($query->get(), 'hod', 'validé');
            } elseif (Auth::user()->isGm()) {
                $query->where('status', MaterialRequestStatus::Progress)->update([
                    'status' => MaterialRequestStatus::Approved,
                    'gm_approval_id' => Auth::user()->id,
                    'expire_at' =>  Carbon::now()->addDays(7),
                ]);
                $this->dispatchApprovalMails($query->get(), 'gm', 'validé');
            }
        }
        $this->reset('selectedRows');
        flash($action . ' applied items successfully.');
    }
}
