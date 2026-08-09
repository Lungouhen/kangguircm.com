<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ThemeSetting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    protected $casts = [
        'value' => 'array',
    ];

    public static function get($key, $default = null)
    {
        return Cache::remember("theme_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set($key, $value, $group = 'global')
    {
        Cache::forget("theme_setting_{$key}");
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

    public static function flushCache()
    {
        Cache::tags(['theme_settings'])->flush();
    }
}
