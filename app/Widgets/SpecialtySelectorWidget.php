<?php

declare(strict_types=1);

namespace App\Widgets;

class SpecialtySelectorWidget implements WidgetInterface
{
    public function getId(): string { return 'specialty_selector'; }
    public function getName(): string { return 'Specialty Selector'; }
    public function getIcon(): string { return '🩺'; }
    public function getFields(): array
    {
        return [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ['name' => 'specialties', 'type' => 'repeater', 'label' => 'Specialties', 'fields' => [['name' => 'name', 'type' => 'text', 'label' => 'Specialty'], ['name' => 'url', 'type' => 'text', 'label' => 'Landing page URL']]],
        ];
    }
    public function render(array $data = []): string
    {
        return view('public.widgets.specialty-selector', $data)->render();
    }
}
