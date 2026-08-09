<?php

namespace App\Widgets;

class TestimonialWidget implements WidgetInterface
{
    public function identifier(): string
    {
        return 'testimonial';
    }

    public function label(): string
    {
        return 'Testimonial Slider';
    }

    public function config(): array
    {
        return [
            'label' => 'Testimonial Slider',
            'fields' => [
                'testimonials' => ['type' => 'repeater', 'label' => 'Testimonials', 'fields' => [
                    'quote' => ['type' => 'textarea', 'label' => 'Quote'],
                    'author' => ['type' => 'text', 'label' => 'Author Name'],
                    'role' => ['type' => 'text', 'label' => 'Author Role'],
                    'avatar' => ['type' => 'image', 'label' => 'Avatar URL'],
                ]],
            ]
        ];
    }

    public function render(array $data): string
    {
        return view('public.widgets.testimonial', $data)->render();
    }
}
