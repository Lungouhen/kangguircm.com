<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageRevision extends Model
{
    protected $fillable = ['page_id','user_id','content','settings','reason'];
    protected $casts = ['content'=>'array','settings'=>'array'];
    public function page(): BelongsTo { return $this->belongsTo(Page::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
