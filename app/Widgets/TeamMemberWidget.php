<?php

namespace App\Widgets;

class TeamMemberWidget implements WidgetInterface
{
    public function identifier(): string
    {
        return 'team_member';
    }

    public function label(): string
    {
        return 'Team Member';
    }

    public function config(): array
    {
        return [
            'label' => 'Team Member',
            'fields' => [
                'name' => ['type' => 'text', 'label' => 'Name'],
                'role' => ['type' => 'text', 'label' => 'Job Title'],
                'image' => ['type' => 'image', 'label' => 'Photo'],
                'bio' => ['type' => 'textarea', 'label' => 'Short Bio'],
                'social_links' => ['type' => 'repeater', 'label' => 'Social Links', 'fields' => [
                    'platform' => ['type' => 'text', 'label' => 'Platform'],
                    'url' => ['type' => 'text', 'label' => 'URL']
                ]]
            ]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.team_member', $data)->render();
    }
}
