<?php

namespace App\Widgets;

class TabsWidget implements WidgetInterface
{
    public function identifier(): string
    {
        return 'tabs';
    }

    public function label(): string
    {
        return 'Tabs';
    }

    public function config(): array
    {
        return [
            'label' => 'Tabs',
            'fields' => [
                'tabs' => ['type' => 'repeater', 'label' => 'Tabs', 'fields' => [
                    'label' => ['type' => 'text', 'label' => 'Tab Label'],
                    'content' => ['type' => 'textarea', 'label' => 'Content']
                ]]
            ]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.tabs', $data)->render();
    }
}
