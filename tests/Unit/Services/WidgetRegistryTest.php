<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\WidgetRegistry;
use App\Widgets\WidgetInterface;
use Tests\TestCase;

class WidgetRegistryTest extends TestCase
{
    public function test_discovered_widgets_implement_the_widget_contract(): void
    {
        $widgets = app(WidgetRegistry::class)->getAll();

        $this->assertNotEmpty($widgets);
        foreach ($widgets as $id => $widget) {
            $this->assertInstanceOf(WidgetInterface::class, $widget);
            $this->assertSame($id, $widget->getId());
            $this->assertNotSame('', $widget->getName());
            $this->assertIsArray($widget->getFields());
        }
    }

    public function test_unknown_widget_renders_a_safe_comment(): void
    {
        $html = app(WidgetRegistry::class)->render('<script>alert(1)</script>');

        $this->assertStringNotContainsString('<script>', $html);
    }
}
