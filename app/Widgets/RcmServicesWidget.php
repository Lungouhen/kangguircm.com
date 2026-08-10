<?php

declare(strict_types=1);

namespace App\Widgets;

class RcmServicesWidget implements WidgetInterface
{
    public function getId(): string { return 'rcm_services'; }
    public function getName(): string { return 'RCM Services'; }
    public function getIcon(): string { return '🏥'; }

    public function getFields(): array
    {
        return [
            ['name' => 'eyebrow', 'type' => 'text', 'label' => 'Eyebrow', 'default' => 'End-to-end revenue cycle management'],
            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'default' => 'Revenue cycle services built around your practice'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Introduction'],
            ['name' => 'services', 'type' => 'repeater', 'label' => 'Services', 'fields' => [
                ['name' => 'icon', 'type' => 'text', 'label' => 'Icon or emoji'],
                ['name' => 'title', 'type' => 'text', 'label' => 'Service name'],
                ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
                ['name' => 'link', 'type' => 'text', 'label' => 'Learn more URL'],
            ]],
        ];
    }

    public function render(array $data = []): string
    {
        return view('public.widgets.rcm-services', $data)->render();
    }
}
