<?php

declare(strict_types=1);

namespace App\Helper;

use App\Enum\MaterialRequestStatus;
use App\Models\MaterialRequest;
use Auth;
use Illuminate\Http\RedirectResponse;

trait ApproveAction
{

    public string $hod_comment = "";
    public string $gm_comment = "";
    public function approveByGm(int $id)
    {
        $request =  MaterialRequest::findOrFail($id);
        $request->update([
            'gm_comment' => $this->gm_comment,
            'gm_approval_date' => now(),
            'gm_approval_id' => Auth::user()->id,
            'status' => MaterialRequestStatus::Approved
        ]);
        flash('Material request approved successfully');
        return to_route('material.index');
    }


    public function approveByHod(int $id): RedirectResponse
    {
        $request =  MaterialRequest::findOrFail($id);
        $request->update([
            'hod_comment' => $this->hod_comment,
            'hod_approval_date' => now(),
            'hod_approval_id' => Auth::user()->id,
            'status' => MaterialRequestStatus::Progress
        ]);

        flash('Material request approved successfully');
        return to_route('material.index');
    }

    public function rejectByHod(int $id)
    {

        if ($this->hod_comment == "") {
            flash('Please add a comment', 'error');
            $this->dispatch('show-modal');
            return \back();
        }
        // $this->validate([
        //     'hod_comment' => 'required|string|min:3',
        // ]);

        // $request =  MaterialRequest::findOrFail($id);
        // $request->update([
        //     'hod_comment' => $this->hod_comment,
        //     'hod_approval_date' => now(),
        //     'hod_approval_id' => Auth::user()->id,
        //     'status' => MaterialRequestStatus::Rejected
        // ]);
        // flash('Material request reject successfully');
        return to_route('material.index');
    }

    public function rejectByGm(int $id): RedirectResponse
    {
        $this->validate([
            'gm_comment' => 'required|string|min:3',
        ]);
        $request =  MaterialRequest::findOrFail($id);
        $request->update([
            'gm_comment' => $this->gm_comment,
            'gm_approval_date' => now(),
            'gm_approval_id' => Auth::user()->id,
            'status' => MaterialRequestStatus::Rejected
        ]);
        flash('Material request reject successfully');
        return to_route('material.index');
    }
}
