<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ThemeController extends Controller
{
    public function edit(): View
    {
        return view('admin.theme.edit', [
            'settings' => [
                'primary_color' => ThemeSetting::get('primary_color', '#2563eb'),
                'secondary_color' => ThemeSetting::get('secondary_color', '#7c3aed'),
                'font_family' => ThemeSetting::get('font_family', 'Inter'),
                'layout_width' => ThemeSetting::get('layout_width', 'boxed'),
                'border_radius' => ThemeSetting::get('border_radius', 'rounded'),
                'dark_mode' => ThemeSetting::get('dark_mode', false),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'secondary_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'font_family' => ['required', 'string', 'max:100'],
            'layout_width' => ['required', 'in:boxed,full'],
            'border_radius' => ['required', 'in:sharp,rounded'],
            'dark_mode' => ['nullable', 'boolean'],
        ]);
        $validated['dark_mode'] = $request->boolean('dark_mode');

        foreach ($validated as $key => $value) {
            ThemeSetting::set($key, $value);
        }

        return redirect()->route('admin.theme.edit')->with('success', 'Theme settings updated.');
    }

    public function preview(): Response
    {
        $primary = ThemeSetting::get('primary_color', '#2563eb');
        $secondary = ThemeSetting::get('secondary_color', '#7c3aed');
        $radius = ThemeSetting::get('border_radius', 'rounded') === 'rounded' ? '0.75rem' : '0';

        return response(":root{--color-primary:{$primary};--color-secondary:{$secondary};--radius-theme:{$radius};}", 200)
            ->header('Content-Type', 'text/css');
    }
}
