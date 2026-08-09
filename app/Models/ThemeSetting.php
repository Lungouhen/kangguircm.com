<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ThemeSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    protected $casts = [
        'value' => 'string', // Keep as string, decode manually if JSON
    ];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        // Decode JSON if type is json
        if ($setting->type === 'json') {
            return json_decode($setting->value, true) ?? $setting->value;
        }

        // Cast booleans
        if ($setting->type === 'boolean') {
            return (bool) $setting->value;
        }

        return $setting->value;
    }

    public static function set($key, $value, $type = 'string', $group = 'general')
    {
        $encodedValue = is_array($value) ? json_encode($value) : $value;
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $encodedValue, 'type' => $type, 'group' => $group]
        );
    }

    public static function flushCache()
    {
        // Cache flushing handled by observer or manual call
    }
}
