<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\CarRequest;
use App\Models\MaterialRequest;
use App\Notifications\UserRequestNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class MailUserRequestNotifJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private CarRequest|MaterialRequest $model,
        public string $message)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $model = $this->model;
        $model->loadMissing('user');
        $model->user?->notify(new UserRequestNotification($this->getRoute(), $this->message));
    }

    private function getRoute(): string
    {
        return $this->model instanceof MaterialRequest
            ? route('material.show', $this->model)
            : route('car.show', $this->model);
    }
}
