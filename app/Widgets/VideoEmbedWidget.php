<?php

namespace App\Widgets;

class VideoEmbedWidget implements WidgetInterface
{
    public function identifier(): string
    {
        return 'video_embed';
    }

    public function label(): string
    {
        return 'Video Embed';
    }

    public function config(): array
    {
        return [
            'label' => 'Video Embed',
            'fields' => [
                'url' => ['type' => 'text', 'label' => 'Video URL (YouTube/Vimeo)'],
                'autoplay' => ['type' => 'boolean', 'label' => 'Autoplay'],
            ]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.video_embed', $data)->render();
    }
}
