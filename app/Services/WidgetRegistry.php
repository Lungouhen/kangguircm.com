<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class WidgetRegistry
{
    protected array $widgets = [];

    public function __construct()
    {
        $this->discoverWidgets();
    }

    /**
     * Auto-discover all widget classes in app/Widgets
     */
    protected function discoverWidgets(): void
    {
        $widgetPath = app_path('Widgets');
        
        if (!File::exists($widgetPath)) {
            return;
        }

        $files = File::files($widgetPath);

        foreach ($files as $file) {
            $className = 'App\\Widgets\\' . pathinfo($file, PATHINFO_FILENAME);
            
            if (class_exists($className)) {
                $widget = new $className();
                $this->register($widget);
            }
        }
    }

    /**
     * Register a widget instance
     */
    public function register(object $widget): void
    {
        $this->widgets[$widget->getId()] = $widget;
    }

    /**
     * Get all registered widgets
     */
    public function getAll(): array
    {
        return $this->widgets;
    }

    /**
     * Get a specific widget by ID
     */
    public function get(string $id): ?object
    {
        return $this->widgets[$id] ?? null;
    }

    /**
     * Render a widget with data
     */
    public function render(string $id, array $data = []): string
    {
        $widget = $this->get($id);
        
        if (!$widget) {
            return '<!-- Widget not found: ' . $id . ' -->';
        }

        return $widget->render($data);
    }
}
