<?php

namespace Pharaonic\Laravel\Sluggable\Tests\Feature;

use InvalidArgumentException;
use Pharaonic\Laravel\Sluggable\Attributes\Slug;
use Pharaonic\Laravel\Sluggable\Tests\Fixtures\Post;
use Pharaonic\Laravel\Sluggable\Tests\TestCase;

class AttributesTest extends TestCase
{
    public function test_default_target_and_attribute_priority_over_property(): void
    {
        $post = new #[Slug(source: 'seo_title')] class extends Post {};
        $post->fill(['title' => 'Ignored', 'seo_title' => 'Hello World'])->save();

        $this->assertSame('hello-world', $post->slug);
        $this->assertContains('slug', $post->getFillable());
    }

    public function test_multiple_targets_use_the_same_engine(): void
    {
        $post = new #[Slug(source: 'title')]
        #[Slug(source: 'title', target: 'seo_slug', separator: '_', unique: false)] class extends Post {};
        $post->fill(['title' => 'Hello World', 'seo_title' => 'News'])->save();

        $this->assertSame('hello-world', $post->slug);
        $this->assertSame('hello_world', $post->seo_slug);
        $copy = new ($post::class)(['title' => 'Hello World', 'seo_title' => 'News']);
        (fn () => $this->generateSlugs(false))->call($copy);
        $this->assertSame('hello-world-2', $copy->slug);
        $this->assertSame('hello_world', $copy->seo_slug);
    }

    public function test_method_wins_even_when_empty_and_ignores_invalid_attributes(): void
    {
        $post = new #[Slug(source: '')] class extends Post
        {
            public function sluggable(): array
            {
                return [];
            }
        };
        $post->fill(['title' => 'Hello'])->save();
        $this->assertNull($post->slug);
    }

    public function test_method_wins_without_merging(): void
    {
        $post = new #[Slug(source: 'title')] class extends Post
        {
            public function sluggable(): array
            {
                return ['seo_slug' => 'seo_title'];
            }
        };
        $post->fill(['title' => 'Ignored', 'seo_title' => 'Chosen'])->save();
        $this->assertNull($post->slug);
        $this->assertSame('chosen', $post->seo_slug);
    }

    public function test_options_and_source_updates(): void
    {
        $post = new #[Slug(source: 'title', separator: '_', unique: false, on_update: true, max_length: 10, ascii_only: true, ascii_lang: 'en')] class extends Post {};
        $post->fill(['title' => 'Hello World'])->save();
        $this->assertSame('hello', $post->slug);
        $post->update(['title' => 'Hello News']);
        $this->assertSame('hello_news', $post->slug);
    }

    public function test_on_create_can_be_disabled(): void
    {
        $post = new #[Slug(source: 'title', on_create: false)] class extends Post {};
        $post->fill(['title' => 'Hello'])->save();
        $this->assertNull($post->slug);
    }

    public function test_include_trashed_checks_deleted_records(): void
    {
        $post = new #[Slug(source: 'title', include_trashed: true)] class extends Post {};
        $post->fill(['title' => 'Hello'])->save();
        $post->delete();
        $copy = new ($post::class)(['title' => 'Hello']);
        $copy->save();
        $this->assertSame('hello-2', $copy->slug);
    }

    public function test_cache_is_per_class_and_does_not_freeze_defaults(): void
    {
        $first = new #[Slug(source: 'title')] class extends Post {};
        $second = new #[Slug(source: 'seo_title')] class extends Post {};
        $first->fill(['title' => 'Hello World'])->save();
        $second->fill(['seo_title' => 'Other Value'])->save();
        $this->assertSame('hello-world', $first->slug);
        $this->assertSame('other-value', $second->slug);
        config(['pharaonic.sluggable.separator' => '_']);
        $copy = new ($first::class)(['title' => 'Hello World']);
        $copy->save();
        $this->assertSame('hello_world', $copy->slug);
    }

    public function test_duplicate_targets_fail_clearly(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Duplicate Sluggable target: slug');
        new #[Slug(source: 'title')] #[Slug(source: 'seo_title')] class extends Post {};
    }

    public function test_invalid_definitions_fail_clearly(): void
    {
        foreach ([['', 'slug'], [' ', 'slug'], ['title', ''], ['title', ' ']] as [$source, $target]) {
            try {
                new Slug(source: $source, target: $target);
                $this->fail('Invalid attribute definition was accepted.');
            } catch (InvalidArgumentException $exception) {
                $this->assertNotEmpty($exception->getMessage());
            }
        }
    }

    public function test_attributes_are_not_inherited(): void
    {
        $post = new AttributeChildPost(['title' => 'Legacy', 'seo_title' => 'Parent']);
        $post->save();
        $this->assertSame('legacy', $post->slug);
        $this->assertNull($post->seo_slug);
    }

    public function test_attribute_is_repeatable_and_class_only(): void
    {
        $meta = (new \ReflectionClass(Slug::class))->getAttributes(\Attribute::class)[0]->newInstance();
        $this->assertSame(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE, $meta->flags);
    }
}

#[Slug(source: 'seo_title', target: 'seo_slug')]
abstract class AttributeBasePost extends Post {}

class AttributeChildPost extends AttributeBasePost {}
