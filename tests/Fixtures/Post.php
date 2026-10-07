<?php

namespace Pharaonic\Laravel\Sluggable\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pharaonic\Laravel\Sluggable\Sluggable;

/**
 * Property API: title -> slug.
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $seo_title
 * @property int|null $category_id
 * @property string|null $slug
 * @property string|null $seo_slug
 * @property string|null $category_slug
 * @property Category|null $category
 */
class Post extends Model
{
    use Sluggable;
    use SoftDeletes;

    protected $table = 'posts';

    /** @var array<int, string> */
    protected $fillable = ['title', 'seo_title', 'category_id'];

    /** @var string */
    protected $sluggable = 'title';

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
