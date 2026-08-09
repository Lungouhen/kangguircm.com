<?php

namespace App\Widgets;

class AccordionWidget implements WidgetInterface
{
    public function identifier(): string
    {
        return 'accordion';
    }

    public function label(): string
    {
        return 'Accordion';
    }

    public function config(): array
    {
        return [
            'label' => 'Accordion',
            'fields' => [
                'items' => ['type' => 'repeater', 'label' => 'Items', 'fields' => [
                    'title' => ['type' => 'text', 'label' => 'Title'],
                    'content' => ['type' => 'textarea', 'label' => 'Content']
                ]]
            ]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.accordion', $data)->render();
    }
}
