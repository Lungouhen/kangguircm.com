<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Jobs\SendNewLeadNotifications;
use App\Models\MarketingLead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class LeadNotificationDispatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_contact_submission_is_saved_before_notification_is_queued(): void
    {
        Queue::fake();
        $response=$this->post('/request-rcm-assessment',[
            'name'=>'Jane Doe','email'=>'jane@example.com','organization'=>'Example Medical Group',
            'specialty'=>'Cardiology','message'=>'Please contact our billing team.','consent'=>'1','source'=>'contact-test',
        ]);
        $response->assertRedirect();
        $lead=MarketingLead::firstOrFail();
        Queue::assertPushed(SendNewLeadNotifications::class,fn($job)=>$job->leadId===$lead->id);
    }
}
