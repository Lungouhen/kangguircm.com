<?php

namespace App\Widgets;

class CounterBoxWidget implements WidgetInterface
{
    public function identifier(): string
    {
        return 'counter_box';
    }

    public function label(): string
    {
        return 'Counter Box';
    }

    public function config(): array
    {
        return [
            'label' => 'Counter Box',
            'fields' => [
                'number' => ['type' => 'text', 'label' => 'Target Number'],
                'prefix' => ['type' => 'text', 'label' => 'Prefix (e.g. +)'],
                'suffix' => ['type' => 'text', 'label' => 'Suffix (e.g. %)'],
                'label' => ['type' => 'text', 'label' => 'Description'],
            ]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.counter_box', $data)->render();
    }
}
