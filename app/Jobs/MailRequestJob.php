<?php

namespace App\Jobs;

use App\Models\User;
use App\Enum\RoleEnum;
use App\Models\CarRequest;
use App\Models\MaterialRequest;
use App\Enum\MaterialRequestStatus;
use Illuminate\Support\Facades\Log;
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
    public function __construct(private CarRequest|MaterialRequest $model, public string $message)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $this->model->loadMissing('user');

        // if ($this->model->status === MaterialRequestStatus::Pending) {
        //     $hodUsers = User::where('department_id', $this->model->user->department_id)
        //         ->where('role', RoleEnum::HOD)
        //         ->get();
        //     if ($this->model instanceof MaterialRequest) {
        //         Notification::send($hodUsers, new UserRequestNotification(route('material.show', $this->model), $this->message));
        //     } elseif ($this->model instanceof CarRequest) {
        //         Notification::send($hodUsers, new UserRequestNotification(route('car.show', $this->model), $this->message));
        //     }
        // } elseif ($this->model->status === MaterialRequestStatus::Progress) {
        //     $gmUsers = User::where('role', RoleEnum::GM)->get();
        //     if ($this->model instanceof MaterialRequest) {
        //         Notification::send($gmUsers, new UserRequestNotification(route('material.show', $this->model), $this->message));
        //     } elseif ($this->model instanceof CarRequest) {
        //         Notification::send($gmUsers, new UserRequestNotification(route('car.show', $this->model), $this->message));
        //     }
        // } else {
        //     if ($this->model instanceof MaterialRequest) {
        //         $this->model->user->notify(new UserRequestNotification(route('material.show', $this->model), $this->message));
        //     } elseif ($this->model instanceof CarRequest) {
        //         $this->model->user->notify(new UserRequestNotification(route('car.show', $this->model), $this->message));
        //     }
        // }

        $this->model->loadMissing('user');
        $routeName = $this->model instanceof MaterialRequest ? 'material.show' : 'car.show';
        $notification = new UserRequestNotification(route($routeName, $this->model), $this->message);

        switch ($this->model->status) {
            case MaterialRequestStatus::Pending:
                $hodUsers = User::where('department_id', $this->model->user->department_id)
                    ->where('role', RoleEnum::HOD)
                    ->get();
                Notification::send($hodUsers, $notification);
                Log::info('Sent notification to HOD users', ['users' => $hodUsers->pluck('id')->toArray()]);
                break;

            case MaterialRequestStatus::Progress:
                $gmUsers = User::where('role', RoleEnum::GM)->get();
                Notification::send($gmUsers, $notification);
                Log::info('Sent notification to GM users', ['users' => $gmUsers->pluck('id')->toArray()]);
                break;

            default:
                $this->model->user->notify($notification);
                Log::info('Sent notification to request user', ['user_id' => $this->model->user->id]);
                break;
        }
    }
}
