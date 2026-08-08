<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\SeoService;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Database\Factories\PostFactory;

/**
 * Unit tests for the SeoService class.
 * 
 * These tests verify the behavior of SEO meta tag generation
 * and structured data methods.
 */
class SeoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up default config values for testing
        Config::set('app.name', 'Test App');
        Config::set('app.url', 'https://test.example.com');
    }

    /**
     * Test generateMeta returns default values when no parameters provided.
     */
    public function test_generate_meta_returns_defaults_when_no_parameters(): void
    {
        $meta = SeoService::generateMeta('', '', null, null);

        $this->assertEquals('Test App', $meta['title']);
        $this->assertEquals('KangGui RCM - CMS, Email Marketing & HRM Platform', $meta['description']);
        $this->assertArrayHasKey('image', $meta);
        $this->assertArrayHasKey('url', $meta);
        $this->assertEquals('website', $meta['type']);
        $this->assertEquals('Test App', $meta['site_name']);
        $this->assertEquals('summary_large_image', $meta['twitter_card']);
    }

    /**
     * Test generateMeta uses provided values over defaults.
     */
    public function test_generate_meta_uses_provided_values(): void
    {
        $meta = SeoService::generateMeta(
            'Custom Title',
            'Custom Description',
            '/images/custom.jpg',
            'https://custom.example.com/page',
            'article'
        );

        $this->assertEquals('Custom Title', $meta['title']);
        $this->assertEquals('Custom Description', $meta['description']);
        $this->assertEquals('/images/custom.jpg', $meta['image']);
        $this->assertEquals('https://custom.example.com/page', $meta['url']);
        $this->assertEquals('article', $meta['type']);
    }

    /**
     * Test generateMeta allows empty strings to fall back to defaults.
     */
    public function test_generate_meta_falls_back_to_defaults_for_empty_strings(): void
    {
        $meta = SeoService::generateMeta('', 'Custom Description', null, null);

        $this->assertEquals('Test App', $meta['title']);
        $this->assertEquals('Custom Description', $meta['description']);
    }

    /**
     * Test generateMeta preserves custom type parameter.
     */
    public function test_generate_meta_preserves_custom_type(): void
    {
        $types = ['article', 'book', 'profile', 'website'];

        foreach ($types as $type) {
            $meta = SeoService::generateMeta('Title', 'Description', null, null, $type);
            $this->assertEquals($type, $meta['type'], "Type should be {$type}");
        }
    }

    /**
     * Test articleSchema generates correct structure for a post.
     */
    public function test_article_schema_generates_correct_structure(): void
    {
        $user = User::factory()->create(['name' => 'Author Name']);
        $post = Post::factory()->create([
            'title' => 'Test Article',
            'excerpt' => 'Test excerpt',
            'author_id' => $user->id,
            'published_at' => now(),
        ]);

        $schema = SeoService::articleSchema($post);

        $this->assertEquals('https://schema.org', $schema['@context']);
        $this->assertEquals('Article', $schema['@type']);
        $this->assertEquals('Test Article', $schema['headline']);
        $this->assertEquals('Test excerpt', $schema['description']);
        $this->assertArrayHasKey('image', $schema);
        $this->assertArrayHasKey('datePublished', $schema);
        $this->assertArrayHasKey('dateModified', $schema);
        $this->assertEquals('Author Name', $schema['author']['name']);
        $this->assertEquals('Test App', $schema['publisher']['name']);
    }

    /**
     * Test articleSchema uses content excerpt when excerpt is null.
     */
    public function test_article_schema_uses_content_when_excerpt_is_null(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'title' => 'Test Article',
            'excerpt' => null,
            'content' => str_repeat('This is a long content. ', 10),
            'author_id' => $user->id,
        ]);

        $schema = SeoService::articleSchema($post);

        // Should use first 160 characters of content
        $expectedDesc = substr(strip_tags($post->content), 0, 160);
        $this->assertEquals($expectedDesc, $schema['description']);
    }

    /**
     * Test articleSchema handles missing author gracefully.
     */
    public function test_article_schema_handles_missing_author(): void
    {
        $post = Post::factory()->create([
            'title' => 'Test Article',
            'author_id' => null,
        ]);

        $schema = SeoService::articleSchema($post);

        $this->assertEquals('Admin', $schema['author']['name']);
    }

    /**
     * Test organizationSchema generates correct structure.
     */
    public function test_organization_schema_generates_correct_structure(): void
    {
        $schema = SeoService::organizationSchema();

        $this->assertEquals('https://schema.org', $schema['@context']);
        $this->assertEquals('Organization', $schema['@type']);
        $this->assertEquals('Test App', $schema['name']);
        $this->assertEquals('https://test.example.com', $schema['url']);
        $this->assertArrayHasKey('logo', $schema);
        $this->assertArrayHasKey('contactPoint', $schema);
        $this->assertEquals('customer service', $schema['contactPoint']['contactType']);
    }

    /**
     * Test breadcrumbSchema generates correct structure.
     */
    public function test_breadcrumb_schema_generates_correct_structure(): void
    {
        $items = [
            ['name' => 'Home', 'url' => 'https://test.example.com'],
            ['name' => 'Blog', 'url' => 'https://test.example.com/blog'],
            ['name' => 'Post Title', 'url' => 'https://test.example.com/blog/post'],
        ];

        $schema = SeoService::breadcrumbSchema($items);

        $this->assertEquals('https://schema.org', $schema['@context']);
        $this->assertEquals('BreadcrumbList', $schema['@type']);
        $this->assertCount(3, $schema['itemListElement']);

        foreach ($schema['itemListElement'] as $index => $item) {
            $this->assertEquals('ListItem', $item['@type']);
            $this->assertEquals($index + 1, $item['position']);
            $this->assertEquals($items[$index]['name'], $item['name']);
            $this->assertEquals($items[$index]['url'], $item['item']);
        }
    }

    /**
     * Test breadcrumbSchema handles empty items array.
     */
    public function test_breadcrumb_schema_handles_empty_items(): void
    {
        $schema = SeoService::breadcrumbSchema([]);

        $this->assertEquals('https://schema.org', $schema['@context']);
        $this->assertEquals('BreadcrumbList', $schema['@type']);
        $this->assertEmpty($schema['itemListElement']);
    }

    /**
     * Test breadcrumbSchema assigns correct positions.
     */
    public function test_breadcrumb_schema_assigns_correct_positions(): void
    {
        $items = [
            ['name' => 'First', 'url' => '/first'],
            ['name' => 'Second', 'url' => '/second'],
            ['name' => 'Third', 'url' => '/third'],
        ];

        $schema = SeoService::breadcrumbSchema($items);

        $positions = array_column($schema['itemListElement'], 'position');
        $this->assertEquals([1, 2, 3], $positions);
    }
}
