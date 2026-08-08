<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        private PostRepository $postRepository
    ) {}

    public function index(Request $request): View
    {
        $posts = $this->postRepository->paginate(
            15,
            $request->input('search'),
            $request->input('status')
        );

        return view('cms.posts.index', compact('posts'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('cms.posts.create', compact('categories'));
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')
                ->store('posts/images', 'public');
        }

        $data['user_id'] = $request->user()->id;

        $this->postRepository->create($data);

        return redirect()
            ->route('cms.posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function show(Post $post): View
    {
        $relatedPosts = $this->postRepository->getPublishedPosts(3)
            ->where('id', '!=', $post->id);

        return view('cms.posts.show', compact('post', 'relatedPosts'));
    }

    public function edit(Post $post): View
    {
        $categories = Category::orderBy('name')->get();
        return view('cms.posts.edit', compact('post', 'categories'));
    }

    public function update(StorePostRequest $request, Post $post): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }

            $data['featured_image'] = $request->file('featured_image')
                ->store('posts/images', 'public');
        }

        $this->postRepository->update($post, $data);

        return redirect()
            ->route('cms.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $this->postRepository->delete($post);

        return redirect()
            ->route('cms.posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    public function publish(Post $post): RedirectResponse
    {
        $this->postRepository->publish($post);

        return back()->with('success', 'Post published successfully.');
    }

    public function draft(Post $post): RedirectResponse
    {
        $this->postRepository->draft($post);

        return back()->with('success', 'Post moved to drafts.');
    }
}
