<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Widgets\WidgetInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use ReflectionClass;

class WidgetManagerController extends Controller
{
    public function index(): View
    {
        return view('admin.widgets.index', [
            'widgets' => $this->installed(),
            'disabled' => SiteSetting::valueOf('disabled_widgets', []),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $installed = array_keys($this->installed());
        $enabled = $request->validate(['enabled' => ['nullable', 'array'], 'enabled.*' => ['string']])['enabled'] ?? [];
        SiteSetting::put('disabled_widgets', array_values(array_diff($installed, $enabled)), 'modules');

        return back()->with('success', 'Widget modules updated. Existing disabled blocks remain stored but are not rendered.');
    }

    private function installed(): array
    {
        $widgets = [];
        foreach (File::files(app_path('Widgets')) as $file) {
            $class = 'App\\Widgets\\'.$file->getFilenameWithoutExtension();
            if (!class_exists($class)) continue;
            $reflection = new ReflectionClass($class);
            if (!$reflection->isInstantiable() || !$reflection->implementsInterface(WidgetInterface::class)) continue;
            $widget = $reflection->newInstance();
            $widgets[$widget->getId()] = $widget;
        }
        ksort($widgets);
        return $widgets;
    }
}
