<?php

declare(strict_types=1);

use App\Models\PageVisit;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function (): void {
    $this->comment(Illuminate\Foundation\Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('analytics:prune {--days=}', function (): void {
    $days=(int)($this->option('days')?:SiteSetting::valueOf('analytics_retention_days',config('analytics.retention_days',180)));
    $days=max(30,min(730,$days));
    $deleted=PageVisit::where('created_at','<',now()->subDays($days))->delete();
    $this->info("Deleted {$deleted} analytics records older than {$days} days.");
})->purpose('Prune privacy-first page analytics data');

Schedule::command('analytics:prune')->dailyAt('02:30')->withoutOverlapping();
