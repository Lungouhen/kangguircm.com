<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\SeoRedirect;
use App\Repositories\PostRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        private PostRepository $postRepository
    ) {}

    public function index(): View
    {
        $posts = $this->postRepository->paginate(15);
        return view('cms.posts.index', compact('posts'));
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('cms.posts.create', compact('categories'));
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        $data['author_id'] = $request->user()->id;

        $this->postRepository->create($data);

        return redirect()
            ->route('admin.cms.posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function show(int $id): View
    {
        $post = $this->postRepository->find($id);

        if (!$post) {
            abort(404);
        }

        return view('cms.posts.show', compact('post'));
    }

    public function edit(int $id): View
    {
        $post = $this->postRepository->find($id);

        if (!$post) {
            abort(404);
        }

        $categories = Category::all();
        return view('cms.posts.edit', compact('post', 'categories'));
    }

    public function update(UpdatePostRequest $request, int $id): RedirectResponse
    {
        $post = $this->postRepository->find($id);

        if (!$post) {
            abort(404);
        }

        $data = $request->validated();
        $oldPath = '/blog/'.$post->slug;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        $this->postRepository->update($post, $data);
        if ($oldPath !== '/blog/'.$post->slug) {
            SeoRedirect::query()->updateOrCreate(
                ['source_path' => $oldPath],
                ['destination_path' => '/blog/'.$post->slug, 'status_code' => 301, 'is_active' => true]
            );
        }

        return redirect()
            ->route('admin.cms.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $post = $this->postRepository->find($id);

        if (!$post) {
            abort(404);
        }

        $this->postRepository->delete($post);

        return redirect()
            ->route('admin.cms.posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    public function publish(int $id): RedirectResponse
    {
        $post = $this->postRepository->find($id);

        if (!$post) {
            abort(404);
        }

        $this->postRepository->publish($post);

        return redirect()
            ->route('admin.cms.posts.index')
            ->with('success', 'Post published successfully.');
    }
}
