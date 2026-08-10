<?php

declare(strict_types=1);

namespace App\Widgets;

class IntegrationCompatibilityWidget implements WidgetInterface
{
    public function getId(): string { return 'integration_compatibility'; }
    public function getName(): string { return 'Technology Integrations'; }
    public function getIcon(): string { return '🔌'; }
    public function getFields(): array
    {
        return [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ['name' => 'integrations', 'type' => 'repeater', 'label' => 'Integrations', 'fields' => [['name' => 'name', 'type' => 'text', 'label' => 'System name'], ['name' => 'category', 'type' => 'text', 'label' => 'Category'], ['name' => 'logo', 'type' => 'image', 'label' => 'Logo']]],
            ['name' => 'footnote', 'type' => 'text', 'label' => 'Footnote'],
        ];
    }
    public function render(array $data = []): string
    {
        return view('public.widgets.integration-compatibility', $data)->render();
    }
}
