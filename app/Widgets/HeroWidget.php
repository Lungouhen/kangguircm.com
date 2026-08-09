<?php

namespace App\Widgets;

class HeroWidget implements WidgetInterface
{
    public function getId(): string
    {
        return 'hero';
    }

    public function getName(): string
    {
        return 'Hero Section';
    }

    public function getIcon(): string
    {
        return '🚀';
    }

    public function getFields(): array
    {
        return [
            ['name' => 'title', 'type' => 'text', 'label' => 'Headline', 'required' => true],
            ['name' => 'subtitle', 'type' => 'textarea', 'label' => 'Subheadline'],
            ['name' => 'button_text', 'type' => 'text', 'label' => 'Button Text'],
            ['name' => 'button_url', 'type' => 'url', 'label' => 'Button URL'],
            ['name' => 'background_image', 'type' => 'image', 'label' => 'Background Image'],
            ['name' => 'overlay_color', 'type' => 'color', 'label' => 'Overlay Color', 'default' => '#000000'],
        ];
    }

    public function render(array $data = []): string
    {
        $title = $data['title'] ?? 'Welcome';
        $subtitle = $data['subtitle'] ?? '';
        $btnText = $data['button_text'] ?? '';
        $btnUrl = $data['button_url'] ?? '#';
        $bgImage = $data['background_image'] ?? null;
        $overlay = $data['overlay_color'] ?? '#000000';

        $bgStyle = $bgImage ? "background-image: url('$bgImage'); background-size: cover;" : 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);';

        return view('public.widgets.hero', compact('title', 'subtitle', 'btnText', 'btnUrl', 'bgStyle', 'overlay'))->render();
    }
}
