## Examples

### 1. Blog Posts With Slug URLs

A post gets a slug from its title, and the controller looks it up by slug.

- ===Migration

  ```php title="database/migrations/2020_01_01_000000_create_posts_table.php"
  Schema::create('posts', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('title');
      $table->text('body');
      $table->sluggable();
      $table->timestamps();
  });
  ```

- ===Model

  ```php title="app/Post.php"
  namespace App;

  use Illuminate\Database\Eloquent\Model;
  use Pharaonic\Laravel\Sluggable\Sluggable;

  class Post extends Model
  {
      use Sluggable;

      protected $fillable = ['title', 'body'];

      protected $sluggable = 'title';
  }
  ```

- ===Controller

  ```php title="app/Http/Controllers/PostController.php"
  namespace App\Http\Controllers;

  use App\Post;

  class PostController extends Controller
  {
      public function show(string $slug)
      {
          return view('posts.show', [
              'post' => Post::findBySlugOrFail($slug),
          ]);
      }
  }
  ```

- ===Route

  ```php title="routes/web.php"
  Route::get('/posts/{slug}', 'PostController@show')->name('posts.show');
  ```

### 2. Products With SEO and Category Slugs

One product, three independent slugs, one of them from a relation.

```php title="app/Product.php"
class Product extends Model
{
    use Sluggable;

    protected $fillable = ['name', 'seo_title', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => 'name',
            'seo_slug' => ['source' => 'seo_title', 'on_update' => true],
            'category_slug' => fn ($model) => $model->category?->name ?? '',
        ];
    }
}
```

```php
$product = Product::create([
    'name' => 'Galaxy S24 Ultra',
    'seo_title' => 'Buy Galaxy S24 Ultra Online',
    'category_id' => $phones->id,
]);

$product->slug;          // "galaxy-s24-ultra"
$product->seo_slug;      // "buy-galaxy-s24-ultra-online"
$product->category_slug; // "phones"
```

### 3. Arabic Titles: Unicode or ASCII

By default the slug keeps Arabic letters:

```php
Post::create(['title' => 'مرحبا بالعالم'])->slug; // "مرحبا-بالعالم"
```

For ASCII-only URLs, enable transliteration in the config:

```php title="config/pharaonic/sluggable.php"
'ascii_only' => true,
```

### 4. Stable Slugs With a Unique Key Prefix

Keep `on_update` disabled so shared links never break, and use `slug_with_key` when you want the key in the URL:

```php
$post = Post::create(['title' => 'Release Notes']);

route('posts.show', $post->slug_with_key); // "/posts/12-release-notes"
```

```php title="app/Http/Controllers/PostController.php"
public function show(string $slugWithKey)
{
    $id = (int) strtok($slugWithKey, '-');

    return view('posts.show', ['post' => Post::findOrFail($id)]);
}
```

### 5. Short Slugs for Share Links

Limit one slug's length without affecting the others:

```php
public function sluggable(): array
{
    return [
        'slug' => 'title',
        'share_slug' => ['source' => 'title', 'max_length' => 20],
    ];
}
```

```php
$post = Post::create(['title' => 'A very long title about Laravel slugs']);

$post->slug;       // "a-very-long-title-about-laravel-slugs"
$post->share_slug; // "a-very-long-title"
```
