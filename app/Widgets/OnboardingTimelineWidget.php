<?php

declare(strict_types=1);

namespace App\Widgets;

class OnboardingTimelineWidget implements WidgetInterface
{
    public function getId(): string { return 'onboarding_timeline'; }
    public function getName(): string { return 'Client Onboarding Timeline'; }
    public function getIcon(): string { return '🚀'; }
    public function getFields(): array
    {
        return [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ['name' => 'steps', 'type' => 'repeater', 'label' => 'Steps', 'fields' => [['name' => 'title', 'type' => 'text', 'label' => 'Title'], ['name' => 'description', 'type' => 'textarea', 'label' => 'Description']]],
        ];
    }
    public function render(array $data = []): string
    {
        return view('public.widgets.onboarding-timeline', $data)->render();
    }
}
