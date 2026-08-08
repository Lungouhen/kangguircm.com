<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Unit tests for the Post model.
 * 
 * These tests verify the behavior of the Post model methods,
 * including scopes, status transitions, and relationships.
 */
class PostModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a post can be created with required attributes.
     */
    public function test_post_can_be_created_with_required_attributes(): void
    {
        $author = User::factory()->create();
        
        $post = Post::factory()->create([
            'title' => 'Test Post',
            'slug' => 'test-post',
            'author_id' => $author->id,
        ]);

        $this->assertEquals('Test Post', $post->title);
        $this->assertEquals('test-post', $post->slug);
        $this->assertEquals(Post::STATUS_DRAFT, $post->status);
        $this->assertEquals(0, $post->views);
    }

    /**
     * Test published scope returns only published posts.
     */
    public function test_published_scope_returns_only_published_posts(): void
    {
        $author = User::factory()->create();
        
        $publishedPost = Post::factory()->published()->create(['author_id' => $author->id]);
        $draftPost = Post::factory()->draft()->create(['author_id' => $author->id]);
        $archivedPost = Post::factory()->archived()->create(['author_id' => $author->id]);

        $publishedPosts = Post::published()->get();

        $this->assertTrue($publishedPosts->contains($publishedPost));
        $this->assertFalse($publishedPosts->contains($draftPost));
        $this->assertFalse($publishedPosts->contains($archivedPost));
    }

    /**
     * Test draft scope returns only draft posts.
     */
    public function test_draft_scope_returns_only_draft_posts(): void
    {
        $author = User::factory()->create();
        
        $publishedPost = Post::factory()->published()->create(['author_id' => $author->id]);
        $draftPost = Post::factory()->draft()->create(['author_id' => $author->id]);

        $draftPosts = Post::draft()->get();

        $this->assertTrue($draftPosts->contains($draftPost));
        $this->assertFalse($draftPosts->contains($publishedPost));
    }

    /**
     * Test publish method changes status to published.
     */
    public function test_publish_method_changes_status_to_published(): void
    {
        $post = Post::factory()->draft()->create();

        $this->assertEquals(Post::STATUS_DRAFT, $post->status);
        $this->assertNull($post->published_at);

        $post->publish();

        $this->assertEquals(Post::STATUS_PUBLISHED, $post->fresh()->status);
        $this->assertNotNull($post->fresh()->published_at);
    }

    /**
     * Test draft method changes status to draft.
     */
    public function test_draft_method_changes_status_to_draft(): void
    {
        $post = Post::factory()->published()->create();

        $this->assertEquals(Post::STATUS_PUBLISHED, $post->status);
        $this->assertNotNull($post->published_at);

        $post->draft();

        $this->assertEquals(Post::STATUS_DRAFT, $post->fresh()->status);
        $this->assertNull($post->fresh()->published_at);
    }

    /**
     * Test incrementViews method increases view count.
     */
    public function test_increment_views_method_increases_view_count(): void
    {
        $post = Post::factory()->create(['views' => 0]);

        $this->assertEquals(0, $post->views);

        $post->incrementViews();

        $this->assertEquals(1, $post->fresh()->views);

        $post->incrementViews();
        $post->incrementViews();

        $this->assertEquals(3, $post->fresh()->views);
    }

    /**
     * Test post belongs to author.
     */
    public function test_post_belongs_to_author(): void
    {
        $author = User::factory()->create(['name' => 'Author Name']);
        $post = Post::factory()->create(['author_id' => $author->id]);

        $this->assertInstanceOf(User::class, $post->author);
        $this->assertEquals('Author Name', $post->author->name);
    }

    /**
     * Test post can have multiple categories.
     */
    public function test_post_can_have_multiple_categories(): void
    {
        $post = Post::factory()->create();
        $category1 = Category::factory()->create(['name' => 'Tech']);
        $category2 = Category::factory()->create(['name' => 'Programming']);

        $post->categories()->attach([$category1->id, $category2->id]);

        $this->assertEquals(2, $post->categories->count());
        $this->assertTrue($post->categories->contains($category1));
        $this->assertTrue($post->categories->contains($category2));
    }

    /**
     * Test slug is generated from title.
     */
    public function test_slug_generation_from_title(): void
    {
        $post = Post::factory()->create(['title' => 'This Is A Test Title!']);

        $this->assertEquals('this-is-a-test-title', $post->slug);
    }

    /**
     * Test post status constants are defined correctly.
     */
    public function test_post_status_constants_are_defined(): void
    {
        $this->assertEquals('draft', Post::STATUS_DRAFT);
        $this->assertEquals('published', Post::STATUS_PUBLISHED);
        $this->assertEquals('archived', Post::STATUS_ARCHIVED);
    }

    /**
     * Test published_at is cast to datetime.
     */
    public function test_published_at_is_cast_to_datetime(): void
    {
        $post = Post::factory()->published()->create();

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $post->published_at);
    }

    /**
     * Test views is cast to integer.
     */
    public function test_views_is_cast_to_integer(): void
    {
        $post = Post::factory()->create(['views' => '100']);

        $this->assertIsInt($post->views);
        $this->assertEquals(100, $post->views);
    }

    /**
     * Test factory creates valid post by default.
     */
    public function test_factory_creates_valid_post(): void
    {
        $post = Post::factory()->create();

        $this->assertNotNull($post->title);
        $this->assertNotNull($post->slug);
        $this->assertNotNull($post->content);
        $this->assertNotNull($post->author_id);
    }

    /**
     * Test factory withPublished state creates published post.
     */
    public function test_factory_published_state(): void
    {
        $post = Post::factory()->published()->create();

        $this->assertEquals(Post::STATUS_PUBLISHED, $post->status);
        $this->assertNotNull($post->published_at);
    }

    /**
     * Test factory withArchived state creates archived post.
     */
    public function test_factory_archived_state(): void
    {
        $post = Post::factory()->archived()->create();

        $this->assertEquals(Post::STATUS_ARCHIVED, $post->status);
    }
}
