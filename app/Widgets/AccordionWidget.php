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
            'fields' => ['title' => ['type' => 'text', 'label' => 'Title'], 'items' => ['type' => 'repeater', 'label' => 'Items', 'fields' => ['heading' => 'Heading', 'content' => 'Content']]]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.accordion', $data)->render();
    }
}
