<?php

declare(strict_types=1);

namespace App\Widgets;

class RevenueLeakageCalculatorWidget implements WidgetInterface
{
    public function getId(): string { return 'revenue_leakage_calculator'; }
    public function getName(): string { return 'Revenue Leakage Calculator'; }
    public function getIcon(): string { return '🧮'; }
    public function getFields(): array
    {
        return [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ['name' => 'cta_text', 'type' => 'text', 'label' => 'CTA text'],
        ];
    }
    public function render(array $data = []): string
    {
        return view('public.widgets.revenue-leakage-calculator', $data)->render();
    }
}
