<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Récupère la valeur d'un réglage (avec cache), ou la valeur par défaut.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $value = Cache::rememberForever(
            "setting:{$key}",
            fn () => static::query()->where('key', $key)->value('value'),
        );

        return $value ?? $default;
    }

    /**
     * Enregistre (ou met à jour) un réglage et invalide son cache.
     */
    public static function put(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => (string) $value]);
        Cache::forget("setting:{$key}");
    }
}
