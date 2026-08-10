<?php

declare(strict_types=1);
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
class LegalPolicy extends Model
{
 use SoftDeletes;
 public const TYPES=['privacy','terms','cookies','hipaa','disclaimer','accessibility','analytics'];
 protected $fillable=['type','title','slug','version','effective_at','content','is_published','show_in_footer'];
 protected $casts=['effective_at'=>'date','is_published'=>'boolean','show_in_footer'=>'boolean'];
 protected static function booted(): void{$clear=fn()=>Cache::forget('cms_legal_footer');static::saved($clear);static::deleted($clear);}
}
