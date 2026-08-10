<?php

declare(strict_types=1);

namespace App\Widgets;

class EngagementModelsWidget implements WidgetInterface
{
    public function getId(): string { return 'engagement_models'; }
    public function getName(): string { return 'Engagement Models'; }
    public function getIcon(): string { return '🤝'; }
    public function getFields(): array
    {
        return [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ['name' => 'models', 'type' => 'repeater', 'label' => 'Models', 'fields' => [['name' => 'name', 'type' => 'text', 'label' => 'Name'], ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'], ['name' => 'best_for', 'type' => 'text', 'label' => 'Best for']]],
        ];
    }
    public function render(array $data = []): string
    {
        return view('public.widgets.engagement-models', $data)->render();
    }
}
