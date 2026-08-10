<?php

declare(strict_types=1);

namespace App\Widgets;

class CaseStudyWidget implements WidgetInterface
{
    public function getId(): string { return 'case_study'; }
    public function getName(): string { return 'RCM Case Study'; }
    public function getIcon(): string { return '📋'; }
    public function getFields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'text', 'label' => 'Eyebrow', 'default' => 'Client success story'],
            ['name' => 'title', 'type' => 'text', 'label' => 'Headline'],
            ['name' => 'specialty', 'type' => 'text', 'label' => 'Practice specialty'],
            ['name' => 'challenge', 'type' => 'textarea', 'label' => 'Challenge'],
            ['name' => 'solution', 'type' => 'textarea', 'label' => 'Solution'],
            ['name' => 'results', 'type' => 'repeater', 'label' => 'Results', 'fields' => [
                ['name' => 'value', 'type' => 'text', 'label' => 'Value'],
                ['name' => 'label', 'type' => 'text', 'label' => 'Label'],
            ]],
            ['name' => 'button_text', 'type' => 'text', 'label' => 'Button text'],
            ['name' => 'button_url', 'type' => 'text', 'label' => 'Button URL'],
        ];
    }
    public function render(array $data = []): string { return view('public.widgets.case-study', $data)->render(); }
}
