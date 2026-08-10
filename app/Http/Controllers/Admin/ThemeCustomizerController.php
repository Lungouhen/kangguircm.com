<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;

class ThemeCustomizerController extends Controller
{
    public function index()
    {
        return view('admin.theme-customizer.index');
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

        ThemeSetting::flushCache();

        return redirect()->back()->with('success', 'Theme settings updated successfully!');
    }

    public function reset()
    {
        ThemeSetting::flushCache();
        ThemeSetting::truncate();
        return redirect()->back()->with('success', 'Theme reset to defaults.');
    }
}
