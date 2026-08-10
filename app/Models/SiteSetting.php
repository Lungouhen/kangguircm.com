<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'group'];
    protected $casts = ['value' => 'json'];

    protected static function booted(): void
    {
        static::saved(fn (self $setting) => Cache::forget('site_setting.'.$setting->key));
        static::deleted(fn (self $setting) => Cache::forget('site_setting.'.$setting->key));
    }

    public static function valueOf(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever('site_setting.'.$key, fn () => self::query()->where('key', $key)->first()?->value ?? $default);
    }

    public static function put(string $key, mixed $value, string $group = 'general'): self
    {
        return self::query()->updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
    }
}
