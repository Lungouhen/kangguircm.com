<?php

declare(strict_types=1);
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class VisitorConsent extends Model
{
 protected $fillable=['visitor_hash','necessary','analytics','marketing','preferences','policy_version','action','consented_at'];
 protected $casts=['necessary'=>'boolean','analytics'=>'boolean','marketing'=>'boolean','preferences'=>'boolean','consented_at'=>'datetime'];
}
