<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\PageRenderer;
use Illuminate\View\View;

class PagePreviewController extends Controller
{
    public function __invoke(Page $page, PageRenderer $renderer): View
    {
        return view('public.page', [
            'page'=>$page,
            'previewMode'=>true,
            'hasHero'=>collect($page->content)->contains(fn(array $block): bool => ($block['type']??null)==='hero'),
            'renderedContent'=>$renderer->render($page),
        ]);
    }
}
