<?php

declare(strict_types=1);
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class FormField extends Model
{
 public const TYPES=['text','email','tel','number','textarea','select','radio','checkbox','date'];
 protected $fillable=['form_id','name','label','type','placeholder','help_text','options','is_required','min_length','max_length','sort_order','width'];
 protected $casts=['options'=>'array','is_required'=>'boolean','min_length'=>'integer','max_length'=>'integer','sort_order'=>'integer','width'=>'integer'];
 public function form(): BelongsTo{return $this->belongsTo(Form::class);}
}
