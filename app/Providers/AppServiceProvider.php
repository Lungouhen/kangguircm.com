<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\WhatsAppClient;
use App\Services\WhatsApp\MetaWhatsAppClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(WhatsAppClient::class, MetaWhatsAppClient::class);
    }

    public function boot(): void {}
}
