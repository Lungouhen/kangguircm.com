<?php

namespace App\Widgets;

class LogoGridWidget implements WidgetInterface
{
    public function identifier(): string
    {
        return 'logogrid';
    }

    public function label(): string
    {
        return 'LogoGrid';
    }

    public function config(): array
    {
        return [
            'label' => 'LogoGrid',
            'fields' => ['logos' => ['type' => 'repeater', 'label' => 'Logos', 'fields' => ['image' => 'Image URL', 'link' => 'Link URL']]]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.logogrid', $data)->render();
    }
}
