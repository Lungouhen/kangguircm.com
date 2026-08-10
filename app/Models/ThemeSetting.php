<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ThemeSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, mixed $default = null): mixed
    {
        [$root, $nested] = array_pad(explode('.', $key, 2), 2, null);
        $value = Cache::rememberForever("theme_setting.{$root}", function () use ($root): mixed {
            $stored = self::query()->where('key', $root)->value('value');
            if ($stored === null) {
                return null;
            }

            $decoded = json_decode((string) $stored, true);

            return json_last_error() === JSON_ERROR_NONE ? $decoded : $stored;
        });

        if ($value === null) {
            return $default;
        }

        return $nested ? data_get($value, $nested, $default) : $value;
    }

    public static function set(string $key, mixed $value): self
    {
        $setting = self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => json_encode($value, JSON_THROW_ON_ERROR)]
        );
        Cache::forget("theme_setting.{$key}");

        return $setting;
    }

    public static function flushCache(): void
    {
        foreach (self::query()->pluck('key') as $key) {
            Cache::forget("theme_setting.{$key}");
        }
    }
}
