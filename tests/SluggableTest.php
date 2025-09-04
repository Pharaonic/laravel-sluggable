<?php

namespace Pharaonic\Laravel\Sluggable\Tests;

use Pharaonic\Laravel\Sluggable\Tests\Models\Article;

class SluggableTest extends TestCase
{
    public function test_it_can_generate_a_slug()
    {
        $article = Article::create([
            'title' => 'Test Title',
        ]);

        $this->assertSame('test-title', $article->slug);
        $this->assertSame('1-test-title', $article->slugWithKey);
    }

    public function test_it_can_generate_slug_in_ascii_only()
    {
        config(['pharaonic.sluggable.ascii_only' => true]);

        $article = Article::create([
            'title' => 'تجربة عنوان',
        ]);

        $this->assertSame('tgrb-aanoan', $article->slug);
    }

    public function test_it_can_find_by_slug()
    {
        Article::create([
            'title' => 'Test Title',
        ]);

        $this->assertModelExists(Article::findBySlug('test-title'));
        $this->assertTrue(Article::whereSlug('test-title')->exists());
    }

    public function test_it_can_fail_to_find_by_slug()
    {
        $this->assertNull(Article::findBySlug('non-existent-slug'));
    }
}
