<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Middleware\PermissionMiddleware;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class CmsPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PermissionMiddleware::class);
    }

    public function test_published_page_renders_saved_widgets(): void
    {
        $user = User::factory()->create();
        Page::create([
            'author_id' => $user->id,
            'title' => 'Cardiology Billing',
            'slug' => 'cardiology-billing',
            'content' => [[
                'id' => 'hero-1',
                'type' => 'hero',
                'data' => ['title' => 'Better Cardiology Collections'],
            ]],
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/cardiology-billing')
            ->assertOk()
            ->assertSee('Better Cardiology Collections');
    }

    public function test_draft_page_is_not_public(): void
    {
        $page = Page::create([
            'author_id' => User::factory()->create()->id,
            'title' => 'Draft',
            'slug' => 'draft-page',
            'content' => [],
        ]);

        $this->get('/'.$page->slug)->assertNotFound();
    }

    public function test_authenticated_user_can_save_valid_builder_blocks(): void
    {
        $user = User::factory()->create();
        $page = Page::create([
            'author_id' => $user->id,
            'title' => 'Landing',
            'slug' => 'landing',
            'content' => [],
        ]);

        $response = $this->actingAs($user)->putJson(route('admin.pages.builder.update', $page), [
            'blocks' => [[
                'id' => 'block-1',
                'type' => 'rcm_services',
                'data' => ['title' => 'Our RCM Services'],
                'style' => [],
            ]],
            'lock_version' => $page->lock_version,
        ]);

        $response->assertOk();
        $this->assertSame('rcm_services', $page->fresh()->content[0]['type']);
    }

    public function test_changing_a_page_slug_creates_a_permanent_redirect(): void
    {
        $user = User::factory()->create();
        $page = Page::create([
            'author_id' => $user->id,
            'title' => 'Old Service',
            'slug' => 'old-service',
            'content' => [],
        ]);

        $this->actingAs($user)->put(route('admin.pages.update', $page), [
            'title' => 'New Service',
            'slug' => 'new-service',
            'template' => 'default',
            'schema_type' => 'WebPage',
        ])->assertRedirect(route('admin.pages.index'));

        $this->get('/old-service')->assertStatus(301)->assertRedirect('/new-service');
    }

    public function test_draft_page_can_be_viewed_with_temporary_signed_preview(): void
    {
        $page=Page::create(['author_id'=>User::factory()->create()->id,'title'=>'Draft Preview','slug'=>'draft-preview','content'=>[]]);
        $url=URL::temporarySignedRoute('pages.preview',now()->addMinutes(10),['page'=>$page]);
        $this->get($url)->assertOk()->assertSee('Draft preview');
    }

    public function test_deleted_page_can_be_restored_from_trash(): void
    {
        $user=User::factory()->create();
        $page=Page::create(['author_id'=>$user->id,'title'=>'Recover Me','slug'=>'recover-me','content'=>[]]);
        $this->actingAs($user)->delete(route('admin.pages.destroy',$page))->assertRedirect();
        $this->assertSoftDeleted($page);
        $this->actingAs($user)->post(route('admin.pages.restore',$page->id))->assertRedirect();
        $this->assertNotSoftDeleted($page->fresh());
    }

    public function test_builder_rejects_unknown_widget_types(): void
    {
        $user = User::factory()->create();
        $page = Page::create([
            'author_id' => $user->id,
            'title' => 'Landing',
            'slug' => 'landing',
            'content' => [],
        ]);

        $this->actingAs($user)->putJson(route('admin.pages.builder.update', $page), [
            'blocks' => [['id' => 'bad', 'type' => 'not-a-widget', 'data' => [], 'style' => []]],
            'lock_version' => $page->lock_version,
        ])->assertUnprocessable();
    }
}
