<?php

namespace App\Widgets;

class FeatureGridWidget implements WidgetInterface
{
    public function getId(): string { return 'feature_grid'; }
    public function getName(): string { return 'Feature Grid'; }
    public function getIcon(): string { return '✨'; }

    public function getFields(): array
    {
        return [
            ['name' => 'title', 'type' => 'text', 'label' => 'Section Title'],
            ['name' => 'features', 'type' => 'repeater', 'label' => 'Features', 'fields' => [
                ['name' => 'icon', 'type' => 'text', 'label' => 'Icon Class'],
                ['name' => 'title', 'type' => 'text', 'label' => 'Feature Title'],
                ['name' => 'description', 'type' => 'textarea', 'label' => 'Description']
            ]]
        ];
    }

    public function render(array $data = []): string
    {
        $title = $data['title'] ?? 'Our Features';
        $features = $data['features'] ?? [];
        return view('public.widgets.feature-grid', compact('title', 'features'))->render();
    }
}
