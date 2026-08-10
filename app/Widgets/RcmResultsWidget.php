<?php

declare(strict_types=1);

namespace App\Widgets;

class RcmResultsWidget implements WidgetInterface
{
    public function getId(): string { return 'rcm_results'; }
    public function getName(): string { return 'RCM Performance Metrics'; }
    public function getIcon(): string { return '📈'; }
    public function getFields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'text', 'label' => 'Eyebrow', 'default' => 'Measurable performance'],
            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'default' => 'Results your practice can see'],
            ['name' => 'disclaimer', 'type' => 'text', 'label' => 'Results disclaimer'],
            ['name' => 'metrics', 'type' => 'repeater', 'label' => 'Metrics', 'fields' => [
                ['name' => 'value', 'type' => 'text', 'label' => 'Value'],
                ['name' => 'label', 'type' => 'text', 'label' => 'Label'],
                ['name' => 'context', 'type' => 'text', 'label' => 'Context'],
            ]],
        ];
    }
    public function render(array $data = []): string { return view('public.widgets.rcm-results', $data)->render(); }
}
