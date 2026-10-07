<?php

namespace Pharaonic\Laravel\Sluggable\Tests\Feature;

use Pharaonic\Laravel\Sluggable\Tests\Fixtures\Post;
use Pharaonic\Laravel\Sluggable\Tests\TestCase;

class MaxLengthTest extends TestCase
{
    public function test_the_slug_is_limited_to_max_length(): void
    {
        config(['pharaonic.sluggable.max_length' => 10]);

        $this->assertLessThanOrEqual(10, mb_strlen(Post::create(['title' => 'Hello wonderful world'])->slug));
    }

    public function test_suffixes_never_exceed_max_length(): void
    {
        config(['pharaonic.sluggable.max_length' => 10]);

        $slugs = collect(range(1, 12))->map(fn () => Post::create(['title' => 'abcdefghij'])->slug);

        $this->assertSame('abcdefghij', $slugs[0]);
        $this->assertSame('abcdefgh-2', $slugs[1]);
        $this->assertSame($slugs->count(), $slugs->unique()->count());

        foreach ($slugs as $slug) {
            $this->assertLessThanOrEqual(10, mb_strlen($slug), $slug);
        }
    }

    public function test_null_max_length_means_no_limit(): void
    {
        config(['pharaonic.sluggable.max_length' => null]);

        $title = str_repeat('word ', 80);

        $this->assertSame(399, mb_strlen(Post::create(['title' => $title])->slug));
    }
}
