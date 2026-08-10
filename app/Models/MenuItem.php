<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class MenuItem extends Model
{
    protected $fillable = ['menu_id', 'parent_id', 'label', 'type', 'page_id', 'url', 'target', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];

    protected static function booted(): void
    {
        $clear = fn (self $item) => Cache::forget('cms_menu.'.$item->menu()->value('location'));
        static::saved($clear);
        static::deleted($clear);
    }

    public function menu(): BelongsTo { return $this->belongsTo(Menu::class); }
    public function page(): BelongsTo { return $this->belongsTo(Page::class); }
    public function children(): HasMany { return $this->hasMany(self::class, 'parent_id')->where('is_active', true)->orderBy('sort_order'); }

    public function resolvedUrl(): string
    {
        return $this->type === 'page' && $this->page?->is_published && $this->page->published_at?->isPast()
            ? route('page.show', $this->page->slug)
            : ($this->url ?: '#');
    }
}
