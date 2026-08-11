<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

final class RequestAuditObserver
{
    public function created(Model $model): void
    {
        $this->log($model, 'created', null);
    }

    public function updated(Model $model): void
    {
        $changes = collect($model->getChanges())
            ->except(['updated_at'])
            ->all();

        if ($changes === []) {
            return;
        }

        $this->log($model, 'updated', $changes);
    }

    public function deleted(Model $model): void
    {
        $this->log($model, 'deleted', null);
    }

    private function log(Model $model, string $event, ?array $changes): void
    {
        ActivityLog::create([
            'subject_type' => class_basename($model),
            'subject_id' => $model->getKey(),
            'subject_ref' => $model->reference ?? null,
            'event' => $event,
            'causer_id' => Auth::id(),
            'causer_name' => Auth::user()?->name,
            'changes' => $changes,
        ]);
    }
}
