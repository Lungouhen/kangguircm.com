<?php

declare(strict_types=1);

namespace App\Widgets;

class ComplianceTrustWidget implements WidgetInterface
{
    public function getId(): string { return 'compliance_trust'; }
    public function getName(): string { return 'Compliance & Trust'; }
    public function getIcon(): string { return '🛡️'; }
    public function getFields(): array
    {
        return [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'default' => 'Security and compliance at every touchpoint'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ['name' => 'standards', 'type' => 'repeater', 'label' => 'Standards and safeguards', 'fields' => [
                ['name' => 'title', 'type' => 'text', 'label' => 'Name'],
                ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ]],
            ['name' => 'note', 'type' => 'textarea', 'label' => 'Compliance note'],
        ];
    }
    public function render(array $data = []): string { return view('public.widgets.compliance-trust', $data)->render(); }
}
