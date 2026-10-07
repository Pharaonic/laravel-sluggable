<?php

namespace Pharaonic\Laravel\Sluggable\Tests\Feature;

use Pharaonic\Laravel\Sluggable\Tests\Fixtures\Category;
use Pharaonic\Laravel\Sluggable\Tests\Fixtures\MultiSlugPost;
use Pharaonic\Laravel\Sluggable\Tests\TestCase;

class MethodApiTest extends TestCase
{
    protected function tearDown(): void
    {
        MultiSlugPost::$definitions = null;

        parent::tearDown();
    }

    public function test_it_supports_a_single_slug(): void
    {
        MultiSlugPost::$definitions = ['seo_slug' => 'title'];

        $post = MultiSlugPost::create(['title' => 'Hello World']);

        $this->assertSame('hello-world', $post->seo_slug);
    }

    public function test_it_generates_multiple_targets_independently(): void
    {
        $category = Category::create(['name' => 'Web Development']);

        $post = MultiSlugPost::create([
            'title' => 'Hello World',
            'seo_title' => 'Best Hello World',
            'category_id' => $category->id,
        ]);

        $this->assertSame('hello-world', $post->slug);
        $this->assertSame('best-hello-world', $post->seo_slug);
        $this->assertSame('web-development', $post->category_slug);
    }

    public function test_the_method_takes_priority_over_the_property_without_merging(): void
    {
        MultiSlugPost::$definitions = ['category_slug' => 'title'];

        $post = MultiSlugPost::create(['title' => 'Hello World', 'seo_title' => 'Ignored']);

        $this->assertSame('hello-world', $post->category_slug);
        $this->assertNull($post->slug);
        $this->assertNull($post->seo_slug);
    }

    public function test_it_supports_advanced_array_definitions(): void
    {
        MultiSlugPost::$definitions = [
            'slug' => ['source' => 'title', 'separator' => '_'],
            'seo_slug' => ['source' => 'seo_title', 'unique' => false],
        ];

        MultiSlugPost::create(['title' => 'Hello World', 'seo_title' => 'Same']);
        $post = new MultiSlugPost(['title' => 'Hello World', 'seo_title' => 'Same']);
        (fn () => $this->generateSlugs(false))->call($post);

        $this->assertSame('hello_world_2', $post->slug);
        $this->assertSame('same', $post->seo_slug);
    }

    public function test_it_mixes_simple_and_advanced_definitions(): void
    {
        MultiSlugPost::$definitions = [
            'slug' => 'title',
            'seo_slug' => ['source' => fn () => 'Computed Value', 'separator' => '.'],
        ];

        $post = MultiSlugPost::create(['title' => 'Hello World']);

        $this->assertSame('hello-world', $post->slug);
        $this->assertSame('computed.value', $post->seo_slug);
    }

    public function test_a_callable_source_can_read_a_relation(): void
    {
        $category = Category::create(['name' => 'News']);

        $post = MultiSlugPost::create(['title' => 'Hello', 'category_id' => $category->id]);

        $this->assertSame('news', $post->category_slug);
    }

    public function test_a_callable_source_can_return_a_computed_value(): void
    {
        MultiSlugPost::$definitions = [
            'slug' => fn ($model) => $model->title.' '.$model->seo_title,
        ];

        $post = MultiSlugPost::create(['title' => 'Hello', 'seo_title' => 'World']);

        $this->assertSame('hello-world', $post->slug);
    }

    public function test_an_empty_source_leaves_the_slug_null(): void
    {
        $post = MultiSlugPost::create(['title' => 'Hello']);

        $this->assertNull($post->category_slug);
        $this->assertNull($post->seo_slug);
    }

    public function test_string_sources_matching_php_functions_are_still_attributes(): void
    {
        MultiSlugPost::$definitions = ['slug' => 'date'];

        $post = new MultiSlugPost();
        $post->setRawAttributes(['date' => 'Launch Day']);
        $post->unsetRelation('date');

        $this->assertSame('launch-day', $this->callProtected($post, 'makeSlug', [
            $this->callProtected($post, 'getSluggableDefinitions')['slug'],
        ]));
    }

    public function test_it_supports_an_unlimited_number_of_definitions(): void
    {
        MultiSlugPost::$definitions = [];

        foreach (['slug', 'seo_slug', 'category_slug'] as $i => $column) {
            MultiSlugPost::$definitions[$column] = fn () => "Value {$i}";
        }

        $post = MultiSlugPost::create();

        $this->assertSame(['value-0', 'value-1', 'value-2'], [$post->slug, $post->seo_slug, $post->category_slug]);
    }

    private function callProtected(object $object, string $method, array $args = [])
    {
        return (fn () => $this->{$method}(...$args))->call($object);
    }
}
