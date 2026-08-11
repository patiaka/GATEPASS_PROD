<?php

namespace App\Events;

use App\Models\CarRequest;
use App\Models\MaterialRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestCancelled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public CarRequest|MaterialRequest $model) {}
}
