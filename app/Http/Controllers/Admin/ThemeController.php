<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function edit()
    {
        $settings = ThemeSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.theme.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'font_family' => 'required|string|max:100',
            'layout_width' => 'required|in:boxed,full',
            'border_radius' => 'required|in:sharp,rounded',
            'dark_mode' => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            ThemeSetting::set($key, $value, 'string', 'appearance');
        }

        return redirect()->route('admin.theme.edit')
            ->with('success', 'Theme settings updated successfully.');
    }

    public function preview()
    {
        // Return CSS variables for dynamic theming
        $settings = ThemeSetting::where('group', 'appearance')->get();
        $css = ':root {';
        
        foreach ($settings as $setting) {
            $css .= "--{$setting->key}: {$setting->value};";
        }
        
        $css .= '}';
        
        return response($css, 200, ['Content-Type' => 'text/css']);
    }
}
