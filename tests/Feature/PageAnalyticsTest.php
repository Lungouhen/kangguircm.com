<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\PageVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PageAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        config(['analytics.require_consent'=>false]);
    }

    public function test_public_html_page_records_anonymous_visit_without_raw_ip(): void
    {
        $this->withHeader('User-Agent','Mozilla/5.0 Test Browser')->get('/')->assertOk();
        $visit=PageVisit::first();
        $this->assertNotNull($visit);
        $this->assertSame('/',$visit->path);
        $this->assertSame(64,strlen($visit->visitor_hash));
        $this->assertArrayNotHasKey('ip_address',$visit->getAttributes());
        $this->assertArrayNotHasKey('user_agent',$visit->getAttributes());
    }

    public function test_trusted_geo_and_client_headers_are_reduced_to_report_dimensions(): void
    {
        config(['analytics.trust_geo_headers'=>true]);
        $this->withHeaders([
            'User-Agent'=>'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0) AppleWebKit/605.1.15 Version/17.0 Mobile Safari/604.1',
            'Accept-Language'=>'en-US,en;q=0.9','CF-IPCountry'=>'IN','X-Geo-Region'=>'Punjab','X-Geo-City'=>'Ludhiana',
            'X-Geo-Organization'=>'Example Network','Referer'=>'https://www.google.com/search?q=rcm',
        ])->get('/')->assertOk();
        $visit=PageVisit::firstOrFail();
        $this->assertSame('IN',$visit->country_code);
        $this->assertSame('Ludhiana',$visit->city);
        $this->assertSame('iOS',$visit->operating_system);
        $this->assertSame('Safari',$visit->browser);
        $this->assertSame('search',$visit->reach_type);
        $this->assertSame('mobile',$visit->device_type);
    }

    public function test_do_not_track_prevents_analytics_recording(): void
    {
        $this->withHeaders(['User-Agent'=>'Mozilla/5.0 Test Browser','DNT'=>'1'])->get('/')->assertOk();
        $this->assertDatabaseCount('page_visits',0);
    }

    public function test_duplicate_page_view_is_suppressed_during_cooldown(): void
    {
        $headers=['User-Agent'=>'Mozilla/5.0 Test Browser'];
        $this->withHeaders($headers)->get('/')->assertOk();
        $this->withHeaders($headers)->get('/')->assertOk();
        $this->assertDatabaseCount('page_visits',1);
    }
}
