<?php

namespace App\Jobs;

use App\Models\User;
use App\Enum\RoleEnum;
use App\Models\CarRequest;
use App\Models\MaterialRequest;
use App\Enum\MaterialRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserRequestNotification;

class MailRequestJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public CarRequest|MaterialRequest $model, public string $message)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->model->loadMissing('user');

        if ($this->model->status === MaterialRequestStatus::Pending) {
            $hodUsers = User::where('department_id', $this->model->user->department_id)
                ->where('role', RoleEnum::HOD)
                ->get();
            Notification::send($hodUsers, new UserRequestNotification($this->model, $this->message));
        } else {
            $this->model->user->notify(new UserRequestNotification($this->model, $this->message));
        }
    }
}
