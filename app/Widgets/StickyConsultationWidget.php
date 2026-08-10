<?php

declare(strict_types=1);

namespace App\Widgets;

class StickyConsultationWidget implements WidgetInterface
{
    public function getId(): string { return 'sticky_consultation'; }
    public function getName(): string { return 'Sticky Consultation CTA'; }
    public function getIcon(): string { return '📞'; }
    public function getFields(): array
    {
        return [
            ['name' => 'message', 'type' => 'text', 'label' => 'Message'],
            ['name' => 'button_text', 'type' => 'text', 'label' => 'Button text'],
            ['name' => 'button_url', 'type' => 'text', 'label' => 'Button URL'],
        ];
    }
    public function render(array $data = []): string
    {
        return view('public.widgets.sticky-consultation', $data)->render();
    }
}
