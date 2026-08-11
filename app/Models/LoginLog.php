<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class LoginLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'ip_address',
        'user_agent',
    ];
}
