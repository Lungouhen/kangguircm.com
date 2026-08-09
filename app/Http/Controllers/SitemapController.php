<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $pages = Page::where('status', 'published')->latest()->get();
        $posts = Post::where('status', 'published')->latest()->get();
        $categories = Category::has('posts')->get();

        return response()->view('sitemap.index', [
            'pages' => $pages,
            'posts' => $posts,
            'categories' => $categories,
        ])->header('Content-Type', 'text/xml');
    }
}
