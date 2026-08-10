<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Page;
use Illuminate\Support\HtmlString;

class PageRenderer
{
    public function __construct(
        private readonly WidgetRegistry $widgets,
        private readonly BlockStyleRenderer $styles,
    ) {}

    public function render(Page $page): HtmlString
    {
        $html = collect($page->content ?? [])
            ->filter(fn ($block): bool => is_array($block) && isset($block['type']))
            ->map(function (array $block): string {
                $widget = $this->widgets->render(
                    (string) $block['type'],
                    is_array($block['data'] ?? null) ? $block['data'] : []
                );
                return $this->styles->wrap($widget, $block);
            })
            ->implode("\n");

        return new HtmlString($html);
    }
}
