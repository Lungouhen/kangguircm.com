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
        $btnUrl = $this->safeUrl($data['button_url'] ?? '#');
        $bgImage = $this->safeUrl($data['background_image'] ?? '', true);
        $overlay = preg_match('/^#[0-9a-fA-F]{6}$/', (string) ($data['overlay_color'] ?? ''))
            ? $data['overlay_color']
            : '#000000';

        return view('public.widgets.hero', compact('title', 'subtitle', 'btnText', 'btnUrl', 'bgImage', 'overlay'))->render();
    }

    private function safeUrl(string $url, bool $allowEmpty = false): string
    {
        if ($allowEmpty && $url === '') {
            return '';
        }
        if (str_starts_with($url, '/') || str_starts_with($url, '#')) {
            return $url;
        }

        return filter_var($url, FILTER_VALIDATE_URL) && in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)
            ? $url
            : '#';
    }
}
