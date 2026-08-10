<?php

namespace App\Widgets;

class TabsWidget implements WidgetInterface
{
    use LegacyWidgetAdapter;
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
            'fields' => ['tabs' => ['type' => 'repeater', 'label' => 'Tabs', 'fields' => ['label' => 'Label', 'content' => 'Content']]]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.tabs', $data)->render();
    }
}
