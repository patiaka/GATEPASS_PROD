<?php

declare(strict_types=1);

namespace App\Models;

use App\Helper\DateFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use DateFormat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'director_id'];

    /**
     * Get all of the users for the Department
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function director(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'director_id');
    }
}
