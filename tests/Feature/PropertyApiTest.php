<?php

namespace Pharaonic\Laravel\Sluggable\Tests\Feature;

use Pharaonic\Laravel\Sluggable\Tests\Fixtures\Post;
use Pharaonic\Laravel\Sluggable\Tests\TestCase;

class PropertyApiTest extends TestCase
{
    public function test_it_creates_the_slug_column_from_the_source(): void
    {
        $post = Post::create(['title' => 'Hello World']);

        $this->assertSame('hello-world', $post->slug);
        $this->assertSame('hello-world', $post->fresh()->slug);
    }

    public function test_it_uses_the_config_defaults(): void
    {
        config(['pharaonic.sluggable.separator' => '_']);

        $this->assertSame('hello_world', Post::create(['title' => 'Hello World'])->slug);
    }

    public function test_it_respects_a_manual_slug(): void
    {
        $post = new Post(['title' => 'Hello World']);
        $post->slug = 'custom-url';
        $post->save();

        $this->assertSame('custom-url', $post->fresh()->slug);
    }

    public function test_the_slug_column_is_fillable(): void
    {
        $post = Post::create(['title' => 'Hello World', 'slug' => 'custom-url']);

        $this->assertSame('custom-url', $post->slug);
    }

    public function test_it_reads_a_direct_attribute_and_never_a_relation_path(): void
    {
        $post = new class(['title' => 'Hello']) extends Post
        {
            protected $sluggable = 'category.name';
        };
        $post->save();

        $this->assertNull($post->slug);
    }

    public function test_it_generates_ascii_slugs_when_enabled(): void
    {
        config(['pharaonic.sluggable.ascii_only' => true]);

        $this->assertSame('creme-brulee', Post::create(['title' => 'Crème brûlée'])->slug);
    }

    public function test_it_keeps_unicode_slugs_by_default(): void
    {
        $this->assertSame('مرحبا-بالعالم', Post::create(['title' => 'مرحبا بالعالم'])->slug);
    }
}
