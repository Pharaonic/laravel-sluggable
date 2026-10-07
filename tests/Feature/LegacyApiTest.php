<?php

namespace Pharaonic\Laravel\Sluggable\Tests\Feature;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Pharaonic\Laravel\Sluggable\Tests\Fixtures\Post;
use Pharaonic\Laravel\Sluggable\Tests\TestCase;

class LegacyApiTest extends TestCase
{
    public function test_it_finds_by_slug(): void
    {
        $post = Post::create(['title' => 'Test Title']);

        $this->assertTrue($post->is(Post::findBySlug('test-title')));
        $this->assertTrue(Post::whereSlug('test-title')->exists());
        $this->assertNull(Post::findBySlug('missing'));
    }

    public function test_find_by_slug_or_fail_throws(): void
    {
        $this->expectException(ModelNotFoundException::class);

        Post::findBySlugOrFail('missing');
    }

    public function test_slug_with_key(): void
    {
        $post = Post::create(['title' => 'Test Title']);

        $this->assertSame($post->id.'-test-title', $post->slug_with_key);
    }

    public function test_blade_directive(): void
    {
        $this->assertSame('<?php echo slug($title); ?>', Blade::compileString('@slug($title)'));
    }

    public function test_migration_macro_creates_the_named_columns(): void
    {
        foreach (['slug', 'seo_slug', 'category_slug'] as $column) {
            $this->assertTrue(Schema::hasColumn('posts', $column));
        }
    }

    public function test_migration_macro_adds_a_unique_index(): void
    {
        $indexes = collect(\DB::select("PRAGMA index_list('posts')"))->where('unique', 1)->pluck('name');

        $this->assertContains('posts_slug_unique', $indexes);
        $this->assertContains('posts_seo_slug_unique', $indexes);
    }
}
