<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Page;
use App\Models\PageRevision;

class PageRevisionService
{
    public function capture(Page $page, ?int $userId, string $reason='manual'): PageRevision
    {
        $revision=$page->revisions()->create([
            'user_id'=>$userId,
            'content'=>$page->content??[],
            'settings'=>$page->only(['title','slug','template','meta_title','meta_description','canonical_url','social_image','schema_type','noindex','is_published','published_at']),
            'reason'=>$reason,
        ]);
        $page->revisions()->latest()->offset(50)->limit(500)->get()->each->delete();
        return $revision;
    }

    public function restore(Page $page, PageRevision $revision): void
    {
        abort_unless($revision->page_id===$page->id,404);
        $page->update(array_merge($revision->settings,['content'=>$revision->content,'lock_version'=>$page->lock_version+1]));
    }
}
