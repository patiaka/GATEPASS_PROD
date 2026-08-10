<?php

declare(strict_types=1);

namespace App\Helper;

use App\Enum\MaterialRequestStatus;
use App\Enum\RoleEnum;
use App\Events\RequestApprovalSubmitted;
use App\Jobs\MailRequestJob;
use App\Models\CarRequest;
use App\Models\MaterialRequest;
use App\Models\Setting;
use Gate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use function to_route;

trait ApproveAction
{
    // public string $hod_comment = '';

    // public string $director_comment = '';

    // public string $gm_comment = '';

    public string $comment = '';

    public string $status = '';

    public function approveByHod(int $id, string $type)
    {
        Gate::authorize('action-approved-request', Auth::user());

        $this->validate([
            'comment' => 'nullable|string|min:3',
            'status' => ['required', Rule::in(['Approved', 'Rejected'])],
        ]);

        if ($type === 'material') {
            $request = MaterialRequest::findOrFail($id);

            $request->update([
                'hod_approval_date' => now(),
                'hod_comment' => $this->comment,
                'hod_approval_id' => Auth::user()->id,
                'status' => $this->status === 'Approved' ? MaterialRequestStatus::Progress->value : MaterialRequestStatus::Rejected->value,
                'next_approver_role' => $this->getNextApprover($request),
            ]);

            RequestApprovalSubmitted::dispatch($request, RoleEnum::HOD->value);

            // $this->dispatchApprovalMail($request, 'hod');
            // MailRequestJob::dispatch($request, 'Awaiting a material gate pass request to approve reference '.$request->reference);

            // $this->reset('hod_comment', 'gm_comment');
            flash()->success('Material request approved successfully');
            $this->reset_filled();

            return to_route('material.pending');
        }

        if ($type === 'vehicle') {

            $request = CarRequest::findOrFail($id);
            $request->update([
                'hod_approval_date' => now(),
                'hod_comment' => $this->comment,
                'hod_approval_id' => Auth::user()->id,
                'status' => $this->status === 'Approved' ? MaterialRequestStatus::Progress->value : MaterialRequestStatus::Rejected->value,
                'next_approver_role' => $this->getNextApprover($request),
            ]);

            RequestApprovalSubmitted::dispatch($request, RoleEnum::HOD->value);

            // $this->dispatchApprovalMail($request, 'hod');
            // MailRequestJob::dispatch($request, 'Awaiting a vehicle gate pass request to approve reference '.$request->reference);

            flash()->success('Vehicle request approved successfully');
            $this->reset_filled();

            return to_route('car.pending');
        }
    }

    public function approveByDirector(int $id, string $type)
    {
        Gate::authorize('action-approved-request', Auth::user());

        $this->validate([
            'comment' => 'nullable|string|min:3',
            'status' => ['required', Rule::in(['Approved', 'Rejected'])],
        ]);

        if ($type === 'material') {
            $request = MaterialRequest::findOrFail($id);
            $request->update([
                'director_approval_date' => now(),
                'director_comment' => $this->comment,
                'director_approval_id' => Auth::user()->id,
                'status' => $this->status === 'Approved' ? MaterialRequestStatus::Progress->value : MaterialRequestStatus::Rejected->value,
                'next_approver_role' => $this->getNextApprover($request)
            ]);

            RequestApprovalSubmitted::dispatch($request, RoleEnum::DIRECTOR->value);
            // $this->dispatchApprovalMail($request, 'director');
            // MailRequestJob::dispatch($request, 'Awaiting a material gate pass request to approve reference '.$request->reference);

            // $this->reset('hod_comment', 'gm_comment', 'director_comment');
            flash()->success('Material request approved successfully');
            $this->reset_filled();

            return to_route('material.pending');
        }

        if ($type === 'vehicle') {

            $request = CarRequest::findOrFail($id);
            $request->update([
                'director_approval_date' => now(),
                'director_comment' => $this->comment,
                'director_approval_id' => Auth::user()->id,
                'status' => $this->status === 'Approved' ? MaterialRequestStatus::Progress->value : MaterialRequestStatus::Rejected->value,
                'next_approver_role' => $this->getNextApprover($request)
            ]);

            RequestApprovalSubmitted::dispatch($request, RoleEnum::DIRECTOR->value);
            // $this->dispatchApprovalMail($request, 'director');
            // MailRequestJob::dispatch($request, 'Awaiting a vehicle gate pass request to approve reference '.$request->reference);

            flash()->success('Vehicle request approved successfully');
            $this->reset_filled();

            return to_route('car.pending');
        }
    }

    public function approveByGm(int $id, string $type)
    {
        Gate::authorize('action-approved-request', Auth::user());

        $this->validate([
            'comment' => 'nullable|string|min:3',
            'status' => ['required', Rule::in(['Approved', 'Rejected'])],
        ]);

        if ($type === 'material') {
            $request = MaterialRequest::findOrFail($id);
            $request->update([
                'gm_comment' => $this->comment,
                'gm_approval_date' => now(),
                'gm_approval_id' => Auth::user()->id,
                'status' => $this->status,
                'expire_at' => Carbon::now()->addDays($this->materialValidityDays()),
                'next_approver_role' => null,
            ]);

            // $this->dispatchApprovalMail($request, 'gm');
            RequestApprovalSubmitted::dispatch($request, RoleEnum::GM->value);

            flash()->success('Material request approved successfully');
            $this->reset_filled();

            return to_route('material.pending');
        }

        if ($type === 'vehicle') {
            $request = CarRequest::findOrFail($id);
            $request->update([
                'gm_comment' => $this->comment,
                'gm_approval_date' => now(),
                'gm_approval_id' => Auth::user()->id,
                'status' => $this->status,
                'expire_at' => Carbon::now()->addDays($this->materialValidityDays()),
                'next_approver_role' => null,
            ]);

            // $this->dispatchApprovalMail($request, 'gm');
            RequestApprovalSubmitted::dispatch($request, RoleEnum::GM->value);

            flash()->success('Vehicle request approved successfully');
            $this->reset_filled();

            return to_route('car.pending');
        }
    }

    public function bulkAction(string $action, string $type): void
    {
        Gate::authorize('action-approved-request', Auth::user());

        if (! in_array($action, ['approve', 'reject'], true)) {
            return;
        }

        $user = Auth::user();
        $approved = $action === 'approve';
        $model = $type === 'material' ? MaterialRequest::class : CarRequest::class;

        // Uniquement les demandes en attente de l'action de cet utilisateur
        $requests = $model::query()
            ->whereIn('id', $this->selectedRows)
            ->awaitingApprovalBy($user)
            ->get();

        foreach ($requests as $request) {
            $role = $request->next_approver_role;

            $data = match ($role) {
                RoleEnum::HOD->value => [
                    'hod_approval_date' => now(),
                    'hod_approval_id' => $user->id,
                    'status' => $approved ? MaterialRequestStatus::Progress->value : MaterialRequestStatus::Rejected->value,
                    'next_approver_role' => ! $approved
                        ? null
                        : ($request->isRequiredDirectorApproval() ? RoleEnum::DIRECTOR->value : RoleEnum::GM->value),
                ],
                RoleEnum::DIRECTOR->value => [
                    'director_approval_date' => now(),
                    'director_approval_id' => $user->id,
                    'status' => $approved ? MaterialRequestStatus::Progress->value : MaterialRequestStatus::Rejected->value,
                    'next_approver_role' => $approved ? RoleEnum::GM->value : null,
                ],
                RoleEnum::GM->value => [
                    'gm_approval_date' => now(),
                    'gm_approval_id' => $user->id,
                    'status' => $approved ? MaterialRequestStatus::Approved->value : MaterialRequestStatus::Rejected->value,
                    'expire_at' => Carbon::now()->addDays($this->materialValidityDays()),
                    'next_approver_role' => null,
                ],
                default => null,
            };

            if ($data === null) {
                continue;
            }

            $request->update($data);
            RequestApprovalSubmitted::dispatch($request, $role);
        }

        $this->reset('selectedRows');

        if ($requests->isEmpty()) {
            flash()->warning('No selected request is awaiting your approval.');

            return;
        }

        flash()->success($requests->count().' request(s) '.($approved ? 'approved' : 'rejected').' successfully.');
    }

    /** Durée de validité (en jours) configurable pour les demandes matériel. */
    private function materialValidityDays(): int
    {
        return (int) Setting::get('material_validity_days', 7);
    }

    protected function reset_filled(): void
    {
        // $this->gm_comment = '';
        // $this->hod_comment = '';
        // $this->director_comment = '';
        $this->comment = '';
        $this->status = '';
    }

    private function dispatchApprovalMail($request, string $role)
    {
        $action = $this->status === 'Approved' ? 'valided' : 'rejected';
        $message = "The $role request reference $request->reference has been $action";
        MailRequestJob::dispatch($request, $message);
    }

    private function getNextApprover(CarRequest|MaterialRequest $request): string|null
    {
        $user = Auth::user();

        if (
            $user->isHod() && $this->status === MaterialRequestStatus::Approved->value && 
            $request->isRequiredDirectorApproval()
        ) {
            return RoleEnum::DIRECTOR->value;
        }

        if (
            $user->isHod() && $this->status === MaterialRequestStatus::Approved->value
        ) {
            return RoleEnum::GM->value;
        }

        if ($user->isDirector() && $this->status === MaterialRequestStatus::Approved->value) {
            return RoleEnum::GM->value;
        }

        return null;
    }
}
