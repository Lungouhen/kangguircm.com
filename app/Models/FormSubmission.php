<?php

declare(strict_types=1);
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class FormSubmission extends Model
{
 protected $fillable=['form_id','payload','status','source','landing_page','visitor_hash','consent_text','policy_version','consented_at'];
 protected $casts=['payload'=>'encrypted:array','consented_at'=>'datetime'];
 public function form(): BelongsTo{return $this->belongsTo(Form::class);}
}
