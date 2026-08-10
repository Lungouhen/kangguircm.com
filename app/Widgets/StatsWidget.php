<?php

namespace App\Widgets;

class StatsWidget implements WidgetInterface
{
    use LegacyWidgetAdapter;
    public function identifier(): string
    {
        return 'stats';
    }

    public function label(): string
    {
        return 'Statistics Counter';
    }

    public function config(): array
    {
        return [
            'label' => 'Statistics Counter',
            'fields' => [
                'stats' => ['type' => 'repeater', 'label' => 'Stats Items', 'fields' => [
                    'number' => ['type' => 'text', 'label' => 'Number'],
                    'label' => ['type' => 'text', 'label' => 'Label'],
                    'icon' => ['type' => 'text', 'label' => 'Icon Class (e.g., fa-user)'],
                ]],
            ]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.stats', $data)->render();
    }
}
