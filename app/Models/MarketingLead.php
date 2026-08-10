<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingLead extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'organization', 'specialty', 'provider_count',
        'billing_model', 'monthly_claims', 'primary_challenge', 'message',
        'preferred_contact_time', 'source', 'landing_page', 'utm_source',
        'utm_medium', 'utm_campaign', 'status', 'consent', 'ip_hash',
    ];

    protected function casts(): array
    {
        return [
            'consent' => 'boolean',
            'provider_count' => 'integer',
            'email' => 'encrypted',
            'phone' => 'encrypted',
            'message' => 'encrypted',
            'preferred_contact_time' => 'encrypted',
        ];
    }
}
