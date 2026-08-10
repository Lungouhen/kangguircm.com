<?php

namespace App\Http\Controllers;

use App\Models\ContentEntry;
use App\Models\Page;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $pages = Page::query()->published()->where('noindex', false)->latest('updated_at')->get();
        $posts = Post::query()->published()->where('noindex', false)->latest('updated_at')->get();
        $categories = Category::has('posts')->get();
        $entries = ContentEntry::published()->latest('updated_at')->get();

        return response()->view('sitemap.index', [
            'pages' => $pages,
            'posts' => $posts,
            'categories' => $categories,
            'entries' => $entries,
        ])->withHeaders([
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
