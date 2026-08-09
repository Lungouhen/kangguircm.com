<?php

namespace App\Widgets;

class CounterBoxWidget implements WidgetInterface
{
    public function identifier(): string
    {
        return 'counterbox';
    }

    public function label(): string
    {
        return 'CounterBox';
    }

    public function config(): array
    {
        return [
            'label' => 'CounterBox',
            'fields' => ['counters' => ['type' => 'repeater', 'label' => 'Counters', 'fields' => ['number' => 'Number', 'label' => 'Label', 'icon' => 'Icon Class']]]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.counterbox', $data)->render();
    }
}
