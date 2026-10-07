<?php

namespace Pharaonic\Laravel\Sluggable\Tests\Feature;

use Pharaonic\Laravel\Sluggable\Tests\Fixtures\Post;
use Pharaonic\Laravel\Sluggable\Tests\TestCase;

class SoftDeletesTest extends TestCase
{
    public function test_trashed_records_are_ignored_by_default(): void
    {
        Post::create(['title' => 'Hello'])->delete();

        $this->assertSame('hello', tap(new Post(['title' => 'Hello']), fn ($p) => $this->generate($p))->slug);
    }

    public function test_trashed_records_are_checked_when_include_trashed_is_enabled(): void
    {
        config(['pharaonic.sluggable.include_trashed' => true]);

        Post::create(['title' => 'Hello'])->delete();

        $this->assertSame('hello-2', Post::create(['title' => 'Hello'])->slug);
    }

    private function generate(Post $post): void
    {
        (fn () => $this->generateSlugs(false))->call($post);
    }
}
