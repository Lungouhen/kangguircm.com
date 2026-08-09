<?php

namespace App\Widgets;

class TimelineWidget implements WidgetInterface
{
    public function identifier(): string
    {
        return 'timeline';
    }

    public function label(): string
    {
        return 'Timeline';
    }

    public function config(): array
    {
        return [
            'label' => 'Timeline',
            'fields' => [
                'events' => ['type' => 'repeater', 'label' => 'Events', 'fields' => [
                    'year' => ['type' => 'text', 'label' => 'Year/Date'],
                    'title' => ['type' => 'text', 'label' => 'Title'],
                    'description' => ['type' => 'textarea', 'label' => 'Description']
                ]]
            ]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.timeline', $data)->render();
    }
}
