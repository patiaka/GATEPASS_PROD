<?php

declare(strict_types=1);

namespace App\Helper;

use Auth;
use App\Models\MaterialRequest;
use Illuminate\Validation\Rule;
use App\Enum\MaterialRequestStatus;

trait ApproveAction
{

    public string $hod_comment = "";
    public string $gm_comment = "";
    public string $status = "";

    public function approveByHod(int $id)
    {
        $this->validate([
            'hod_comment' => 'required|string|min:3',
            'status' => ['required', Rule::in(['Approved', 'Rejected'])],
        ]);

        $request =  MaterialRequest::findOrFail($id);
        $request->update([
            'hod_approval_date' => now(),
            'hod_comment' => $this->hod_comment,
            'hod_approval_id' => Auth::user()->id,
            'status' => $this->status === 'Approved' ?  MaterialRequestStatus::Progress->value : MaterialRequestStatus::Rejected->value
        ]);

        flash('Material request approved successfully');
        return to_route('material.index');
    }

    public function approveByGm(int $id)
    {
        $this->validate([
            'gm_comment' => 'required|string|min:3',
            'status' => ['required', Rule::in(['Approved', 'Rejected'])],
        ]);
        $request =  MaterialRequest::findOrFail($id);
        $request->update([
            'gm_comment' => $this->gm_comment,
            'gm_approval_date' => now(),
            'gm_approval_id' => Auth::user()->id,
            'status' => $this->status
        ]);
        flash('Material request approved successfully');
        return to_route('material.index');
    }
}
