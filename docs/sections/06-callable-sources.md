## Callable Sources

A source can be a callable instead of an attribute name. It receives the model and returns the string to slugify. Use it for relations, accessors and computed values.

```php title="app/Models/Post.php"
public function sluggable(): array
{
    return [
        'slug' => 'title',
        'category_slug' => fn ($model) => $model->category?->name ?? '',
        'parent_category_slug' => fn ($model) => $model->category?->parent?->name ?? '',
    ];
}
```

```php
$post = Post::create(['title' => 'Hello', 'category_id' => $news->id]);

$post->category_slug; // "news"
```

### Computed Values

```php
'slug' => fn ($model) => $model->title.' '.$model->published_at->format('Y'),
// "Hello World" + 2024 → "hello-world-2024"
```

### How Sources Are Read

| Source | Read as |
| --- | --- |
| `'title'` | `$model->getAttribute('title')` |
| `'category.name'` | the attribute named `category.name` (not a relation path) |
| `fn ($model) => ...` | the callable's return value |

A string source is always an attribute name, even when it matches a PHP function name such as `'date'`.

### Empty Results

When the source is empty, or slugifies to an empty string, the slug column is set to `null`. The column created by `$table->sluggable()` is nullable for this reason.

:::warning Relations Are Not Loaded For You
The package doesn't eager-load anything. Accessing `$model->category` inside the callable runs a query the first time. When creating many records in a loop, load the relation beforehand or pass the value through an attribute.
:::
