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
            'fields' => ['events' => ['type' => 'repeater', 'label' => 'Events', 'fields' => ['year' => 'Year', 'title' => 'Title', 'description' => 'Description']]]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.timeline', $data)->render();
    }
}
