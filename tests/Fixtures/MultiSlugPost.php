<?php

namespace Pharaonic\Laravel\Sluggable\Tests\Fixtures;

/**
 * Method API with several targets; also defines the property to prove the method wins.
 */
class MultiSlugPost extends Post
{
    /** @var string */
    protected $sluggable = 'seo_title';

    /** @var array<string, mixed>|null */
    public static $definitions;

    /**
     * @return array<string, mixed>
     */
    public function sluggable(): array
    {
        return static::$definitions ?? [
            'slug' => 'title',
            'seo_slug' => 'seo_title',
            'category_slug' => fn (self $model) => optional($model->category)->name ?? '',
        ];
    }
}
