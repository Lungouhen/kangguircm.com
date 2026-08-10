<?php

declare(strict_types=1);
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class Form extends Model
{
 use SoftDeletes;
 protected $fillable=['name','slug','purpose','is_active','description','submit_label','success_message','consent_required','consent_text','policy_version','create_lead'];
 protected $casts=['is_active'=>'boolean','consent_required'=>'boolean','create_lead'=>'boolean'];
 public function fields(): HasMany{return $this->hasMany(FormField::class)->orderBy('sort_order');}
 public function submissions(): HasMany{return $this->hasMany(FormSubmission::class);}
}
