<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\PageRenderer;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function show(PageRenderer $renderer, string $slug): View
    {
        $page = Page::query()->published()->where('slug', $slug)->firstOrFail();

        return view('public.page', [
            'page' => $page,
            'hasHero' => collect($page->content)->contains(fn (array $block): bool => ($block['type'] ?? null) === 'hero'),
            'renderedContent' => $renderer->render($page),
        ]);
    }
}
