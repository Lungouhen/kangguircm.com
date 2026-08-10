<?php

namespace App\Widgets;

class TeamMemberWidget implements WidgetInterface
{
    use LegacyWidgetAdapter;
    public function identifier(): string
    {
        return 'teammember';
    }

    public function label(): string
    {
        return 'TeamMember';
    }

    public function config(): array
    {
        return [
            'label' => 'TeamMember',
            'fields' => ['members' => ['type' => 'repeater', 'label' => 'Members', 'fields' => ['name' => 'Name', 'role' => 'Role', 'image' => 'Image URL', 'social_links' => 'JSON']]]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.teammember', $data)->render();
    }
}
