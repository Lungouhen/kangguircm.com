<?php

declare(strict_types=1);
namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ContentEntry extends Model
{
 use SoftDeletes;
 public const TYPES=['service','specialty','case-study','testimonial','faq','team','location','payer','integration'];
 protected $fillable=['type','title','slug','summary','body','image','data','meta_title','meta_description','status','published_at','sort_order'];
 protected $casts=['data'=>'array','published_at'=>'datetime','sort_order'=>'integer'];
 public function scopePublished(Builder $q): Builder{return $q->where('status','published')->whereNotNull('published_at')->where('published_at','<=',now());}
}
