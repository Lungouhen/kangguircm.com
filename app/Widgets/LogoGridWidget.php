<?php

namespace App\Widgets;

class LogoGridWidget implements WidgetInterface
{
    public function identifier(): string
    {
        return 'logo_grid';
    }

    public function label(): string
    {
        return 'Logo Grid';
    }

    public function config(): array
    {
        return [
            'label' => 'Logo Grid',
            'fields' => [
                'logos' => ['type' => 'repeater', 'label' => 'Logos', 'fields' => [
                    'image' => ['type' => 'image', 'label' => 'Logo Image'],
                    'link' => ['type' => 'text', 'label' => 'Link URL'],
                    'alt' => ['type' => 'text', 'label' => 'Alt Text']
                ]]
            ]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.logo_grid', $data)->render();
    }
}
