<?php

declare(strict_types=1);

namespace App\Widgets;

class PayerCoverageWidget implements WidgetInterface
{
    public function getId(): string { return 'payer_coverage'; }
    public function getName(): string { return 'Payer Coverage'; }
    public function getIcon(): string { return '✅'; }
    public function getFields(): array
    {
        return [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'default' => 'Experience across leading payer networks'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ['name' => 'payers', 'type' => 'repeater', 'label' => 'Payers', 'fields' => [
                ['name' => 'name', 'type' => 'text', 'label' => 'Payer name'],
                ['name' => 'logo', 'type' => 'image', 'label' => 'Logo'],
            ]],
            ['name' => 'footnote', 'type' => 'text', 'label' => 'Trademark footnote'],
        ];
    }
    public function render(array $data = []): string { return view('public.widgets.payer-coverage', $data)->render(); }
}
