<?php

namespace App\Widgets;

class MapLocationWidget implements WidgetInterface
{
    public function identifier(): string
    {
        return 'map_location';
    }

    public function label(): string
    {
        return 'Map Location';
    }

    public function config(): array
    {
        return [
            'label' => 'Map Location',
            'fields' => [
                'address' => ['type' => 'text', 'label' => 'Address'],
                'api_key' => ['type' => 'text', 'label' => 'Google Maps API Key'],
                'zoom' => ['type' => 'number', 'label' => 'Zoom Level', 'default' => 15],
                'height' => ['type' => 'text', 'label' => 'Height (e.g. 400px)', 'default' => '400px']
            ]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.map_location', $data)->render();
    }
}
