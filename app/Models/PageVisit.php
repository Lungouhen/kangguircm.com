<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    public const UPDATED_AT = null;
    protected $fillable = ['visited_on','path','route_name','content_type','content_id','visitor_hash','referrer_host','utm_source','utm_medium','utm_campaign','device_type','country_code','region','city','organization','browser','operating_system','reach_type','language'];
    protected $casts = ['visited_on'=>'date','content_id'=>'integer'];
}
