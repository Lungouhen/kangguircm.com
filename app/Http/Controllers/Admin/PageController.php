<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageRevision;
use App\Models\SeoRedirect;
use App\Services\PageRevisionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(private readonly PageRevisionService $revisions) {}

    public function index(Request $request): View
    {
        $query = $request->string('status')->toString() === 'trash' ? Page::onlyTrashed() : Page::query();
        $pages = $query->with('author')->withCount('visits')
            ->when(in_array($request->string('status')->toString(), ['published','draft'], true), function ($query) use ($request): void {
                $request->string('status')->toString() === 'published'
                    ? $query->published()
                    : $query->where('is_published', false);
            })
            ->latest('updated_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.pages.form', ['page' => new Page()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['author_id'] = $request->user()->id;
        $data['content'] = [];
        $page = Page::create($data);

        return redirect()->route('admin.pages.builder.edit', $page)
            ->with('success', 'Page created. Add sections to begin building.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.form', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $oldPath = '/'.$page->slug;
        $this->revisions->capture($page, $request->user()->id, 'settings');
        $page->update(array_merge($this->validated($request, $page), ['lock_version'=>$page->lock_version+1]));

        if ($oldPath !== '/'.$page->slug) {
            SeoRedirect::query()->updateOrCreate(
                ['source_path' => $oldPath],
                ['destination_path' => '/'.$page->slug, 'status_code' => 301, 'is_active' => true]
            );
        }

        return redirect()->route('admin.pages.index')->with('success', 'Page settings updated.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted.');
    }

    public function revisions(Page $page): View
    {
        return view('admin.pages.revisions', ['page'=>$page, 'revisions'=>$page->revisions()->with('user')->latest()->paginate(25)]);
    }

    public function restoreRevision(Request $request, Page $page, PageRevision $revision): RedirectResponse
    {
        $this->revisions->capture($page, $request->user()->id, 'before-restore');
        $this->revisions->restore($page, $revision);
        return back()->with('success','Revision restored.');
    }

    public function restore(int $page): RedirectResponse
    {
        Page::onlyTrashed()->findOrFail($page)->restore();
        return back()->with('success','Page restored from trash.');
    }

    public function forceDelete(int $page): RedirectResponse
    {
        Page::onlyTrashed()->findOrFail($page)->forceDelete();
        return back()->with('success','Page permanently deleted.');
    }

    private function validated(Request $request, ?Page $page = null): array
    {
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: $request->input('title', '')),
            'is_published' => $request->boolean('is_published'),
            'noindex' => $request->boolean('noindex'),
        ]);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('pages')->ignore($page)],
            'template' => ['required', Rule::in(['default', 'landing', 'full-width'])],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'canonical_url' => ['nullable', 'url', 'max:500'],
            'social_image' => ['nullable', 'string', 'max:500'],
            'schema_type' => ['required', Rule::in(['WebPage', 'AboutPage', 'ContactPage', 'FAQPage', 'MedicalWebPage'])],
            'noindex' => ['boolean'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        if (!$data['is_published']) {
            $data['published_at'] = null;
        }

        return $data;
    }
}
