<?php

namespace Pharaonic\Laravel\Sluggable\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Sluggable\Sluggable;
use Pharaonic\Laravel\Sluggable\Tests\Fixtures\MultiSlugPost;
use Pharaonic\Laravel\Sluggable\Tests\Fixtures\Post;
use Pharaonic\Laravel\Sluggable\Tests\TestCase;

class DefinitionsTest extends TestCase
{
    protected function tearDown(): void
    {
        MultiSlugPost::$definitions = null;

        parent::tearDown();
    }

    public function test_the_property_resolves_to_the_slug_column_with_config_defaults(): void
    {
        config(['pharaonic.sluggable.max_length' => 100]);

        $definitions = $this->definitions(new Post());

        $this->assertSame(['slug'], array_keys($definitions));
        $this->assertSame('title', $definitions['slug']['source']);
        $this->assertSame('-', $definitions['slug']['separator']);
        $this->assertTrue($definitions['slug']['unique']);
        $this->assertFalse($definitions['slug']['on_update']);
        $this->assertFalse($definitions['slug']['include_trashed']);
        $this->assertSame(100, $definitions['slug']['max_length']);
    }

    public function test_the_method_wins_over_the_property(): void
    {
        MultiSlugPost::$definitions = ['seo_slug' => 'title'];

        $this->assertSame(['seo_slug'], array_keys($this->definitions(new MultiSlugPost())));
    }

    public function test_per_slug_options_override_config(): void
    {
        MultiSlugPost::$definitions = ['slug' => ['source' => 'title', 'separator' => '_', 'max_length' => 20]];

        $definition = $this->definitions(new MultiSlugPost())['slug'];

        $this->assertSame('_', $definition['separator']);
        $this->assertSame(20, $definition['max_length']);
        $this->assertTrue($definition['unique']);
    }

    public function test_hard_defaults_apply_when_config_is_missing(): void
    {
        config(['pharaonic.sluggable' => null]);

        $definition = $this->definitions(new Post())['slug'];

        $this->assertSame('-', $definition['separator']);
        $this->assertSame(255, $definition['max_length']);
    }

    public function test_a_model_without_configuration_has_no_definitions(): void
    {
        $model = new class() extends Model
        {
            use Sluggable;
        };

        $this->assertSame([], $this->definitions($model));
    }

    private function definitions(Model $model): array
    {
        return (fn () => $this->getSluggableDefinitions())->call($model);
    }
}
