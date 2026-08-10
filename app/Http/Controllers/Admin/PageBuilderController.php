<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Page;
use App\Services\BlockStyleRenderer;
use App\Services\PageRevisionService;
use App\Services\WidgetRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PageBuilderController extends Controller
{
    public function __construct(private readonly WidgetRegistry $widgets, private readonly PageRevisionService $revisions, private readonly BlockStyleRenderer $styles) {}

    public function edit(Page $page): View
    {
        return view('admin.pages.builder', [
            'page' => $page,
            'widgets' => collect($this->widgets->getAll()),
            'media' => Media::query()->where('mime_type', 'like', 'image/%')->latest()->limit(200)->get(['url', 'alt_text']),
            'previewUrl' => URL::temporarySignedRoute('pages.preview', now()->addHours(2), ['page'=>$page]),
        ]);
    }

    public function update(Request $request, Page $page): JsonResponse
    {
        $data = $request->validate([
            'blocks' => ['present', 'array', 'max:100'],
            'blocks.*.id' => ['required', 'string', 'max:100'],
            'blocks.*.type' => ['required', 'string', 'max:100'],
            'blocks.*.data' => ['present', 'array'],
            'blocks.*.style' => ['present', 'array'],
            'blocks.*.style.background_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.style.text_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'blocks.*.style.padding_top' => ['nullable', 'integer', 'min:0', 'max:200'],
            'blocks.*.style.padding_bottom' => ['nullable', 'integer', 'min:0', 'max:200'],
            'blocks.*.style.container' => ['nullable', 'in:full,boxed'],
            'blocks.*.style.text_align' => ['nullable', 'in:left,center,right'],
            'blocks.*.style.animation' => ['nullable', 'in:none,fade-up,fade-down,fade-left,fade-right,zoom-in'],
            'blocks.*.style.anchor_id' => ['nullable', 'string', 'max:80'],
            'blocks.*.style.css_class' => ['nullable', 'string', 'max:255'],
            'blocks.*.style.hide_mobile' => ['nullable', 'boolean'],
            'blocks.*.style.hide_tablet' => ['nullable', 'boolean'],
            'blocks.*.style.hide_desktop' => ['nullable', 'boolean'],
            'lock_version' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'in:manual,autosave'],
        ]);

        if (collect($data['blocks'])->where('type', 'hero')->count() > 1) {
            throw ValidationException::withMessages([
                'blocks' => 'A page may contain only one hero heading section.',
            ]);
        }

        foreach ($data['blocks'] as $block) {
            if (!$this->widgets->get($block['type'])) {
                throw ValidationException::withMessages([
                    'blocks' => "Unknown widget type: {$block['type']}",
                ]);
            }
        }

        if ($page->lock_version !== $data['lock_version']) {
            return response()->json(['message'=>'This page was changed by another editor. Reload before saving.'], 409);
        }
        if ($page->content !== $data['blocks']) {
            $this->revisions->capture($page, $request->user()->id, $data['reason'] ?? 'manual');
            $page->update(['content'=>$data['blocks'], 'lock_version'=>$page->lock_version+1]);
        }

        return response()->json(['message' => 'Page layout saved.', 'updated_at' => $page->updated_at, 'lock_version'=>$page->lock_version]);
    }

    public function previewWidget(Request $request): JsonResponse
    {
        $data = $request->validate([
            'widget_id' => ['required', 'string', 'max:100'],
            'data' => ['present', 'array'],
            'style' => ['nullable', 'array'],
            'block_id' => ['nullable', 'string', 'max:100'],
        ]);

        if (!$this->widgets->get($data['widget_id'])) {
            abort(404, 'Widget not found.');
        }

        return response()->json([
            'html' => $this->styles->wrap($this->widgets->render($data['widget_id'], $data['data']), ['id'=>$data['block_id']??'preview','style'=>$data['style']??[]]),
        ]);
    }
}
