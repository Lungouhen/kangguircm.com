<?php

namespace App\Widgets;

class PricingWidget implements WidgetInterface
{
    use LegacyWidgetAdapter;
    public function identifier(): string
    {
        return 'pricing';
    }

    public function label(): string
    {
        return 'Pricing Table';
    }

    public function config(): array
    {
        return [
            'label' => 'Pricing Table',
            'fields' => [
                'title' => ['type' => 'text', 'label' => 'Plan Name'],
                'price' => ['type' => 'text', 'label' => 'Price'],
                'features' => ['type' => 'textarea', 'label' => 'Features (one per line)'],
                'cta_text' => ['type' => 'text', 'label' => 'Button Text'],
                'cta_url' => ['type' => 'text', 'label' => 'Button URL'],
                'highlighted' => ['type' => 'boolean', 'label' => 'Highlight this plan?'],
            ]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.pricing', $data)->render();
    }
}
