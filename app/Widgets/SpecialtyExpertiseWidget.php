<?php

declare(strict_types=1);

namespace App\Widgets;

class SpecialtyExpertiseWidget implements WidgetInterface
{
    public function getId(): string { return 'specialty_expertise'; }
    public function getName(): string { return 'Medical Specialties'; }
    public function getIcon(): string { return '🩺'; }
    public function getFields(): array
    {
        return [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'default' => 'RCM expertise for every specialty'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Introduction'],
            ['name' => 'specialties', 'type' => 'repeater', 'label' => 'Specialties', 'fields' => [
                ['name' => 'icon', 'type' => 'text', 'label' => 'Icon or emoji'],
                ['name' => 'name', 'type' => 'text', 'label' => 'Specialty'],
                ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ]],
        ];
    }
    public function render(array $data = []): string { return view('public.widgets.specialty-expertise', $data)->render(); }
}
