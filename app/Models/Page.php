<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'lock_version',
        'template',
        'meta_title',
        'meta_description',
        'canonical_url',
        'social_image',
        'schema_type',
        'noindex',
        'is_published',
        'published_at',
        'author_id',
    ];

    protected $attributes = [
        'content' => '[]',
        'template' => 'default',
        'schema_type' => 'WebPage',
    ];

    protected $casts = [
        'content' => 'array',
        'lock_version' => 'integer',
        'is_published' => 'boolean',
        'noindex' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(PageRevision::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(PageVisit::class, 'content_id')->where('content_type', 'page');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
