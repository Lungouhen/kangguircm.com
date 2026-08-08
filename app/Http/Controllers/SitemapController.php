<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate XML sitemap
     */
    public function index()
    {
        $posts = Cache::remember('sitemap_posts', 3600, function () {
            return Post::where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->get(['slug', 'updated_at']);
        });

        $pages = Cache::remember('sitemap_pages', 3600, function () {
            return Page::where('is_active', true)
                ->whereNull('parent_id')
                ->get(['slug', 'updated_at']);
        });

        return response()->view('sitemap.index', [
            'posts' => $posts,
            'pages' => $pages,
        ], 200, ['Content-Type' => 'application/xml']);
    }
}
