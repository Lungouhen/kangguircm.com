<?php

namespace App\Widgets;

class GalleryWidget implements WidgetInterface
{
    public function identifier(): string
    {
        return 'gallery';
    }

    public function label(): string
    {
        return 'Image Gallery';
    }

    public function config(): array
    {
        return [
            'label' => 'Image Gallery',
            'fields' => [
                'title' => ['type' => 'text', 'label' => 'Gallery Title'],
                'images' => ['type' => 'gallery', 'label' => 'Select Images'],
                'columns' => ['type' => 'select', 'label' => 'Columns', 'options' => [2, 3, 4]],
            ]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.gallery', $data)->render();
    }
}
