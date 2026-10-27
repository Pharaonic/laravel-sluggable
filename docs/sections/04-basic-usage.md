## Basic Usage

Add the `Sluggable` trait to your model and declare the source column with `#[Slug]`. The slug is written to the `slug` column.

```php title="app/Post.php"
namespace App;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Sluggable\Sluggable;
use Pharaonic\Laravel\Sluggable\Attributes\Slug;

#[Slug(source: 'title')]
class Post extends Model
{
    use Sluggable;

    protected $fillable = ['title'];
}
```

See [PHP Attributes](#php-attributes) for additional options and configuration priority.

### Using a Property

You can also configure the same slug using the existing `$sluggable` property:

```php title="app/Post.php"
namespace App;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Sluggable\Sluggable;

class Post extends Model
{
    use Sluggable;

    protected $fillable = ['title'];

    protected $sluggable = 'title';
}
```

### Creating Records

Create records as usual. The slug is generated just before the insert:

```php
$post = Post::create(['title' => 'Hello World']);

$post->slug; // "hello-world"

Post::create(['title' => 'Hello World'])->slug; // "hello-world-2"
```

### Finding by Slug

The trait adds a `whereSlug` scope and two finders on the `slug` column:

```php
Post::findBySlug('hello-world');        // Post or null
Post::findBySlugOrFail('hello-world');  // Post or ModelNotFoundException
Post::whereSlug('hello-world')->first();
```

Route model binding can resolve the model by slug by naming the column in the route:

```php title="routes/web.php"
Route::get('/posts/{post:slug}', function (App\Post $post) {
    return $post;
});
```

To always bind by slug, return the column from `getRouteKeyName()` on the model instead:

```php title="app/Post.php"
public function getRouteKeyName()
{
    return 'slug';
}
```

### Slug With Key

The `slug_with_key` attribute prefixes the slug with the model key, which is handy for URLs that must stay unique even if slugs repeat:

```php
$post->slug_with_key; // "1-hello-world"
```

For models whose class name ends with `Translation` (such as `PostTranslation`), the key of the parent model (`post_id`) is used instead.

### Mass Assignment

When your model uses `$fillable`, the slug columns are added to it automatically, so you can pass a slug to `create()`:

```php
Post::create(['title' => 'Hello World', 'slug' => 'custom-url'])->slug; // "custom-url"
```

:::info Direct Attributes Only
Both `#[Slug(source: 'title')]` and the `$sluggable` property read a direct attribute of the model. `'category.name'` is not treated as a relation path. Use a [callable source](#callable-sources) for that.
:::
