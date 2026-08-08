<?php

namespace App\Listeners;

use App\Enum\RoleEnum;
use App\Events\RequestApprovalSubmitted;
use App\Events\RequestCreated;
use App\Models\CarRequest;
use App\Models\MaterialRequest;
use App\Models\User;
use App\Notifications\UserRequestNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class RequestEventSubscriber
{
    public function handleRequestCreated(RequestCreated $event): void
    {
        $model = $event->model;
        $model->loadMissing('user');

        $model->user?->notify(new UserRequestNotification(
            $this->getRoute($model), 
            sprintf(
                'Your %s request has been created. Reference: %s',
                $model instanceof MaterialRequest ? 'material' : 'vehicle',
                $model->reference
            )
        ));

        Notification::send(
            $this->getHodUsers($model->user->department_id),
            new UserRequestNotification(
                $this->getRoute($model),
                sprintf(
                    'A new %s gate pass request has been created and is awaiting your approval. Reference: %s',
                    $model instanceof MaterialRequest ? 'material' : 'vehicle',
                    $model->reference
                )
            )
        );
    }

    public function handleRequestApprovalSubmitted(RequestApprovalSubmitted $event): void
    {
        $model = $event->model->loadMissing(['user', 'user.department', 'user.department.director']);

        if ($model->status->value === 'Rejected' || $model->status->value === 'Approved')
        {
            $model->user?->notify(new UserRequestNotification(
                $this->getRoute($model), 
                sprintf(
                    'Your %s request has been %s. Reference: %s',
                    $model instanceof MaterialRequest ? 'material' : 'vehicle',
                    strtolower($model->status->value),
                    $model->reference
                )
            ));

            return;
        }

        $director = $model->user->department->director;

        if ($director && !$model->isDirectorApproved()) {
            $nextApprover = $director;
        } else {
            $nextApprover = $this->getGMUsers();
        }

        Notification::send(
            $nextApprover,
            new UserRequestNotification(
                $this->getRoute($model),
                sprintf(
                    'A new %s gate pass request has been created and is awaiting your approval. Reference: %s',
                    $model instanceof MaterialRequest ? 'material' : 'vehicle',
                    $model->reference
                )
            )
        );
    }

    private function getHodUsers(int $departmentId): Collection
    {
        return User::where('department_id', $departmentId)
            ->whereHas('roleAssignments', fn ($q) => $q->where('role', RoleEnum::HOD->value))
            ->get()
        ;
    }

    private function getGMUsers(): Collection
    {
        return User::whereHas('roleAssignments', fn ($q) => $q->where('role', RoleEnum::GM->value))->get();
    }

    private function getRoute(CarRequest|MaterialRequest $model): string
    {
        return route($model instanceof MaterialRequest ? 'material.show' : 'car.show', $model);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, array<int, string>>
     */
    public function subscribe(): array
    {
        return [
            RequestCreated::class => 'handleRequestCreated',
            RequestApprovalSubmitted::class => 'handleRequestApprovalSubmitted',
        ];
    }
}