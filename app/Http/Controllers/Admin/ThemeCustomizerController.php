<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ThemeCustomizerController extends Controller
{
    public function index()
    {
        $settings = ThemeSetting::all()->groupBy('group');
        return view('admin.theme-customizer.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'font_family' => 'required|string',
            'layout_width' => 'required|in:boxed,full',
            'header_style' => 'required|in:transparent,solid,glass',
            'animation_speed' => 'required|numeric|min:0|max:2',
        ]);

        ThemeSetting::set('colors', [
            'primary' => $validated['primary_color'],
            'secondary' => $validated['secondary_color'],
        ]);

        ThemeSetting::set('typography', [
            'font_family' => $validated['font_family'],
        ]);

        ThemeSetting::set('layout', [
            'width' => $validated['layout_width'],
            'header_style' => $validated['header_style'],
        ]);

        ThemeSetting::set('animations', [
            'speed' => $validated['animation_speed'],
        ]);

        Cache::flush();

        return redirect()->back()->with('success', 'Theme settings updated successfully!');
    }

    public function reset()
    {
        ThemeSetting::truncate();
        Cache::flush();
        return redirect()->back()->with('success', 'Theme reset to defaults.');
    }
}
