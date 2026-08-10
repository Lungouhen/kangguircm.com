<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\SiteSetting;
use App\Services\PageRenderer;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(PageRenderer $renderer): View
    {
        $pageId = SiteSetting::valueOf('homepage_id');
        $page = $pageId ? Page::query()->published()->find($pageId) : null;

        if (!$page) {
            return view('public.home');
        }

        return view('public.page', [
            'page' => $page,
            'isHomepage' => true,
            'hasHero' => collect($page->content)->contains(fn (array $block): bool => ($block['type'] ?? null) === 'hero'),
            'renderedContent' => $renderer->render($page),
        ]);
    }
}
