<?php

namespace App\Widgets;

class MapLocationWidget implements WidgetInterface
{
    use LegacyWidgetAdapter;
    public function identifier(): string
    {
        return 'maplocation';
    }

    public function label(): string
    {
        return 'MapLocation';
    }

    public function config(): array
    {
        return [
            'label' => 'MapLocation',
            'fields' => ['api_key' => ['type' => 'text', 'label' => 'Google Maps API Key'], 'address' => ['type' => 'text', 'label' => 'Address'], 'height' => ['type' => 'number', 'label' => 'Height (px)']]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.maplocation', $data)->render();
    }
}
