<?php

namespace App\Widgets;

class VideoEmbedWidget implements WidgetInterface
{
    public function identifier(): string
    {
        return 'videoembed';
    }

    public function label(): string
    {
        return 'VideoEmbed';
    }

    public function config(): array
    {
        return [
            'label' => 'VideoEmbed',
            'fields' => ['video_url' => ['type' => 'text', 'label' => 'Video URL (YouTube/Vimeo)'], 'autoplay' => ['type' => 'boolean', 'label' => 'Autoplay']]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.videoembed', $data)->render();
    }
}
