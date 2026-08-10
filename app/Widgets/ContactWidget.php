<?php

namespace App\Widgets;

class ContactWidget implements WidgetInterface
{
    use LegacyWidgetAdapter;
    public function identifier(): string
    {
        return 'contact';
    }

    public function label(): string
    {
        return 'Contact Form';
    }

    public function config(): array
    {
        return [
            'label' => 'Contact Form',
            'fields' => [
                'title' => ['type' => 'text', 'label' => 'Form Title'],
                'email' => ['type' => 'email', 'label' => 'Recipient Email'],
                'show_map' => ['type' => 'boolean', 'label' => 'Show Map?'],
                'map_embed' => ['type' => 'textarea', 'label' => 'Map Embed Code'],
            ]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.contact', $data)->render();
    }
}
