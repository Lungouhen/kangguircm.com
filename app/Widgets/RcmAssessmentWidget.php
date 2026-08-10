<?php

declare(strict_types=1);

namespace App\Widgets;

class RcmAssessmentWidget implements WidgetInterface
{
    public function getId(): string { return 'rcm_assessment'; }
    public function getName(): string { return 'Free RCM Assessment'; }
    public function getIcon(): string { return '📝'; }
    public function getFields(): array
    {
        return [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ['name' => 'button_text', 'type' => 'text', 'label' => 'Submit button text'],
            ['name' => 'source', 'type' => 'text', 'label' => 'Lead source'],
        ];
    }
    public function render(array $data = []): string
    {
        return view('public.widgets.rcm-assessment', $data)->render();
    }
}
