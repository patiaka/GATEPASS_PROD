<?php

declare(strict_types=1);

namespace App\Helper;

use App\Models\MaterialRequest;
use Auth;
use Illuminate\Http\RedirectResponse;

trait ApproveAction
{

    public string $hod_comment = "";
    public string $gm_comment = "";
    public function approveByGm(int $id): RedirectResponse
    {
        $request =  MaterialRequest::find($id);
        $request->update([
            'gm_comment' => $this->gm_comment,
            'gm_approval_date' => now(),
            'gm_approval_id' => Auth::user()->id
        ]);
        flash('Material request approv successfully');
        return to_route('material.index');
    }

    public function approveByHod(int $id): RedirectResponse
    {
        $request =  MaterialRequest::find($id);
        $request->update([
            'hod_comment' => $this->hod_comment,
            'hod_approval_date' => now(),
            'hod_approval_id' => Auth::user()->id
        ]);

        flash('Material request approved successfully');
        return to_route('material.index');
    }
}
