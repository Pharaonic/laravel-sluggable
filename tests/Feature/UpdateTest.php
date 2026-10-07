<?php

namespace Pharaonic\Laravel\Sluggable\Tests\Feature;

use Pharaonic\Laravel\Sluggable\Tests\Fixtures\Category;
use Pharaonic\Laravel\Sluggable\Tests\Fixtures\MultiSlugPost;
use Pharaonic\Laravel\Sluggable\Tests\Fixtures\Post;
use Pharaonic\Laravel\Sluggable\Tests\TestCase;

class UpdateTest extends TestCase
{
    protected function tearDown(): void
    {
        MultiSlugPost::$definitions = null;

        parent::tearDown();
    }

    public function test_the_slug_is_kept_when_on_update_is_disabled(): void
    {
        $post = Post::create(['title' => 'Hello']);
        $post->update(['title' => 'Goodbye']);

        $this->assertSame('hello', $post->fresh()->slug);
    }

    public function test_the_slug_is_regenerated_when_on_update_is_enabled(): void
    {
        config(['pharaonic.sluggable.on_update' => true]);

        $post = Post::create(['title' => 'Hello']);
        $post->update(['title' => 'Goodbye']);

        $this->assertSame('goodbye', $post->fresh()->slug);
    }

    public function test_a_manual_slug_wins_over_on_update(): void
    {
        config(['pharaonic.sluggable.on_update' => true]);

        $post = Post::create(['title' => 'Hello']);
        $post->update(['title' => 'Goodbye', 'slug' => 'custom']);

        $this->assertSame('custom', $post->fresh()->slug);
    }

    public function test_a_unique_suffix_is_kept_when_the_slug_does_not_change(): void
    {
        config(['pharaonic.sluggable.on_update' => true]);

        Post::create(['title' => 'Hello']);
        $post = Post::create(['title' => 'Hello']);
        $post->update(['title' => 'hello']);

        $this->assertSame('hello-2', $post->fresh()->slug);
    }

    public function test_on_update_can_be_set_per_slug(): void
    {
        MultiSlugPost::$definitions = [
            'slug' => 'title',
            'seo_slug' => ['source' => 'seo_title', 'on_update' => true],
        ];

        $post = MultiSlugPost::create(['title' => 'One', 'seo_title' => 'One']);
        $post->update(['title' => 'Two', 'seo_title' => 'Two']);

        $this->assertSame('one', $post->fresh()->slug);
        $this->assertSame('two', $post->fresh()->seo_slug);
    }

    public function test_callable_sources_are_regenerated_on_update(): void
    {
        MultiSlugPost::$definitions = [
            'category_slug' => ['source' => fn ($model) => $model->category?->name ?? '', 'on_update' => true],
        ];

        $news = Category::create(['name' => 'News']);
        $sport = Category::create(['name' => 'Sport']);

        $post = MultiSlugPost::create(['title' => 'Hello', 'category_id' => $news->id]);
        $post->category_id = $sport->id;
        $post->unsetRelation('category');
        $post->save();

        $this->assertSame('sport', $post->fresh()->category_slug);
    }

    public function test_on_create_can_be_disabled(): void
    {
        config(['pharaonic.sluggable.on_create' => false]);

        $this->assertNull(Post::create(['title' => 'Hello'])->slug);
    }
}
