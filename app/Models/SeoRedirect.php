<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SeoRedirect extends Model
{
    protected $fillable = ['source_path', 'destination_path', 'status_code', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'status_code' => 'integer', 'hits' => 'integer'];

    protected static function booted(): void
    {
        static::saved(fn (self $redirect) => Cache::forget('seo_redirect.'.$redirect->source_path));
        static::deleted(fn (self $redirect) => Cache::forget('seo_redirect.'.$redirect->source_path));
    }

    public static function remember(string $path): ?array
    {
        return Cache::remember('seo_redirect.'.$path, now()->addHour(), fn () => self::query()
            ->where('source_path', $path)->where('is_active', true)
            ->first(['id', 'destination_path', 'status_code'])?->toArray());
    }
}
