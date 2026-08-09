<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ThemeSetting;

class ApplyThemeSettings
{
    public function handle(Request $request, Closure $next)
    {
        view()->share('theme', [
            'primary_color' => ThemeSetting::get('colors.primary', '#2563EB'),
            'secondary_color' => ThemeSetting::get('colors.secondary', '#7C3AED'),
            'font_family' => ThemeSetting::get('typography.font_family', 'Plus Jakarta Sans'),
            'layout_width' => ThemeSetting::get('layout.width', 'boxed'),
            'header_style' => ThemeSetting::get('layout.header_style', 'glass'),
            'animation_speed' => ThemeSetting::get('animations.speed', 0.6),
        ]);

        return $next($request);
    }
}
