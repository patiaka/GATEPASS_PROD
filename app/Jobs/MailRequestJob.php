<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\User;
use App\Enum\RoleEnum;
use App\Models\CarRequest;
use App\Models\MaterialRequest;
use App\Enum\MaterialRequestStatus;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserRequestNotification;

final class MailRequestJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private CarRequest|MaterialRequest $model,
        public string $message
    ) {}


    public function handle(): void
    {
        $this->model->loadMissing('user');


        $recipients = match ($this->model->status) {
            MaterialRequestStatus::Pending => $this->getHodUsers(),
            MaterialRequestStatus::Progress => $this->getGmUsers(),
            default => collect([$this->model->user]),
        };


        Notification::send(
            $recipients,
            new UserRequestNotification($this->getRoute(), $this->message)
        );
    }


    private function getHodUsers(): Collection
    {
        return User::where('department_id', $this->model->user->department_id)
            ->where('role', RoleEnum::HOD)
            ->get();
    }


    private function getGmUsers(): Collection
    {
        return User::where('role', RoleEnum::GM)->get();
    }


    private function getRoute(): string
    {
        return $this->model instanceof MaterialRequest
            ? route('material.show', $this->model)
            : route('car.show', $this->model);
    }
}
