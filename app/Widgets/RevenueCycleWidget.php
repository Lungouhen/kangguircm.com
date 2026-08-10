<?php

declare(strict_types=1);

namespace App\Widgets;

class RevenueCycleWidget implements WidgetInterface
{
    public function getId(): string { return 'revenue_cycle'; }
    public function getName(): string { return 'Revenue Cycle Process'; }
    public function getIcon(): string { return '🔄'; }
    public function getFields(): array
    {
        return [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'default' => 'A healthier revenue cycle, step by step'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Introduction'],
            ['name' => 'steps', 'type' => 'repeater', 'label' => 'Process steps', 'fields' => [
                ['name' => 'title', 'type' => 'text', 'label' => 'Step title'],
                ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ]],
        ];
    }
    public function render(array $data = []): string { return view('public.widgets.revenue-cycle', $data)->render(); }
}
