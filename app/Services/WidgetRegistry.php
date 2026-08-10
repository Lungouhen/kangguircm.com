<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\SiteSetting;
use App\Widgets\WidgetInterface;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class WidgetRegistry
{
    /** @var array<string, WidgetInterface> */
    private array $widgets = [];

    public function __construct()
    {
        $this->discoverWidgets();
    }

    private function discoverWidgets(): void
    {
        $path = app_path('Widgets');
        if (!File::isDirectory($path)) {
            return;
        }

        foreach (File::files($path) as $file) {
            $class = 'App\\Widgets\\'.$file->getFilenameWithoutExtension();
            if (!class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);
            if (!$reflection->isInstantiable() || !$reflection->implementsInterface(WidgetInterface::class)) {
                continue;
            }

            $this->register($reflection->newInstance());
        }

        $disabled = SiteSetting::valueOf('disabled_widgets', []);
        if (is_array($disabled)) {
            $this->widgets = array_diff_key($this->widgets, array_flip($disabled));
        }
        ksort($this->widgets);
    }

    public function register(WidgetInterface $widget): void
    {
        $this->widgets[$widget->getId()] = $widget;
    }

    /** @return array<string, WidgetInterface> */
    public function getAll(): array
    {
        return $this->widgets;
    }

    public function get(string $id): ?WidgetInterface
    {
        return $this->widgets[$id] ?? null;
    }

    public function render(string $id, array $data = []): string
    {
        return $this->get($id)?->render($data) ?? '<!-- Unknown widget: '.e($id).' -->';
    }
}
