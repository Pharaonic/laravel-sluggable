<?php

namespace Pharaonic\Laravel\Sluggable\Tests\Feature;

use Pharaonic\Laravel\Sluggable\Tests\Fixtures\MultiSlugPost;
use Pharaonic\Laravel\Sluggable\Tests\Fixtures\Post;
use Pharaonic\Laravel\Sluggable\Tests\TestCase;

class UniquenessTest extends TestCase
{
    public function test_collisions_get_an_incrementing_suffix(): void
    {
        $slugs = collect(range(1, 4))->map(fn () => Post::create(['title' => 'Hello'])->slug)->all();

        $this->assertSame(['hello', 'hello-2', 'hello-3', 'hello-4'], $slugs);
    }

    public function test_it_uses_the_highest_existing_suffix(): void
    {
        Post::create(['title' => 'Hello']);
        Post::create(['title' => 'x'])->forceFill(['slug' => 'hello-7'])->save();

        $this->assertSame('hello-8', Post::create(['title' => 'Hello'])->slug);
    }

    public function test_similar_prefixes_are_not_collisions(): void
    {
        Post::create(['title' => 'Hello World']);

        $this->assertSame('hello', Post::create(['title' => 'Hello'])->slug);
        $this->assertSame('hello-2', Post::create(['title' => 'Hello'])->slug);
    }

    public function test_it_can_be_disabled(): void
    {
        config(['pharaonic.sluggable.unique' => false]);

        $this->assertSame('hello', Post::create(['title' => 'Hello'])->slug);
        $this->assertSame('hello', tap(new Post(['title' => 'Hello']), fn ($p) => $this->generate($p))->slug);
    }

    public function test_each_target_column_is_checked_independently(): void
    {
        MultiSlugPost::$definitions = ['slug' => 'title', 'seo_slug' => 'seo_title'];

        MultiSlugPost::create(['title' => 'Hello', 'seo_title' => 'World']);
        $post = MultiSlugPost::create(['title' => 'World', 'seo_title' => 'Hello']);

        $this->assertSame('world', $post->slug);
        $this->assertSame('hello', $post->seo_slug);

        MultiSlugPost::$definitions = null;
    }

    public function test_like_wildcards_in_the_slug_are_escaped(): void
    {
        config(['pharaonic.sluggable.separator' => '_']);

        Post::create(['title' => 'a'])->forceFill(['slug' => 'aXb_9'])->save();

        // "a_b" as a LIKE pattern would match "aXb_9" if "_" were not escaped.
        $this->assertSame('a_b', Post::create(['title' => 'a b'])->slug);
        $this->assertSame('a_b_2', Post::create(['title' => 'a b'])->slug);
    }

    public function test_updating_a_model_does_not_conflict_with_itself(): void
    {
        config(['pharaonic.sluggable.on_update' => true]);

        $post = Post::create(['title' => 'Hello']);
        $post->update(['title' => 'HELLO']);

        $this->assertSame('hello', $post->fresh()->slug);
    }

    private function generate(Post $post): void
    {
        (fn () => $this->generateSlugs(false))->call($post);
    }
}
