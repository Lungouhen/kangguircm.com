<?php

declare(strict_types=1);

namespace App\Widgets;

class ClientResultsWidget implements WidgetInterface
{
    public function getId(): string { return 'client_results'; }
    public function getName(): string { return 'Before & After Results'; }
    public function getIcon(): string { return '📊'; }
    public function getFields(): array
    {
        return [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ['name' => 'results', 'type' => 'repeater', 'label' => 'Results', 'fields' => [['name' => 'metric', 'type' => 'text', 'label' => 'Metric'], ['name' => 'before', 'type' => 'text', 'label' => 'Before'], ['name' => 'after', 'type' => 'text', 'label' => 'After']]],
        ];
    }
    public function render(array $data = []): string
    {
        return view('public.widgets.client-results', $data)->render();
    }
}
