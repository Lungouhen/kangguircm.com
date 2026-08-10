<?php

namespace App\Widgets;

class CtaWidget implements WidgetInterface
{
    use LegacyWidgetAdapter;
    public function identifier(): string
    {
        return 'cta';
    }

    public function label(): string
    {
        return 'Call to Action';
    }

    public function config(): array
    {
        return [
            'label' => 'Call to Action',
            'fields' => [
                'title' => ['type' => 'text', 'label' => 'Headline'],
                'description' => ['type' => 'textarea', 'label' => 'Description'],
                'button_text' => ['type' => 'text', 'label' => 'Button Text'],
                'button_url' => ['type' => 'text', 'label' => 'Button URL'],
                'background_image' => ['type' => 'image', 'label' => 'Background Image'],
            ]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.cta', $data)->render();
    }
}
