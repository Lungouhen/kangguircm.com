<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationDelivery extends Model
{
    protected $fillable=['event','notifiable_type','notifiable_id','channel','provider','recipient_hash','recipient_masked','template','status','provider_message_id','attempts','failure_code','sent_at','delivered_at','read_at','failed_at'];
    protected $casts=['sent_at'=>'datetime','delivered_at'=>'datetime','read_at'=>'datetime','failed_at'=>'datetime','attempts'=>'integer'];
    public const PENDING='pending'; public const SENT='sent'; public const DELIVERED='delivered'; public const READ='read'; public const FAILED='failed';
}
