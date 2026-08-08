<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enum\MaterialRequestStatus;
use App\Enum\RoleEnum;
use App\Models\CarRequest;
use App\Models\MaterialRequest;
use App\Models\User;
use App\Notifications\UserRequestNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

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
            ->whereHas('roleAssignments', fn ($q) => $q->where('role', RoleEnum::HOD->value))
            ->get();
    }

    private function getGmUsers(): Collection
    {
        return User::whereHas('roleAssignments', fn ($q) => $q->where('role', RoleEnum::GM->value))->get();
    }

    private function getRoute(): string
    {
        return $this->model instanceof MaterialRequest
            ? route('material.show', $this->model)
            : route('car.show', $this->model);
    }
}
