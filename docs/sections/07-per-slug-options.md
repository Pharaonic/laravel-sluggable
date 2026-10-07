## Per-Slug Options

Use an array with a `source` key to override the config for one slug. Simple and advanced definitions can be mixed in the same method.

```php title="app/Post.php"
public function sluggable(): array
{
    return [
        'slug' => [
            'source' => 'title',
            'separator' => '-',
            'unique' => true,
        ],

        'seo_slug' => [
            'source' => 'seo_title',
            'separator' => '_',
            'on_update' => true,
        ],

        'category_slug' => fn ($model) => $model->category?->name ?? '',
    ];
}
```

### Available Options

| Option | Type | Description |
| --- | --- | --- |
| `source` | `string` or `callable` | Attribute name, or a callable receiving the model and returning a string. Required. |
| `separator` | `string` | Joins the words and the unique suffix. |
| `unique` | `bool` | Append a suffix when the slug is taken in this column. |
| `on_update` | `bool` | Regenerate this slug when its source changes. |
| `include_trashed` | `bool` | Check soft-deleted rows for this slug's uniqueness. |
| `max_length` | `int` or `null` | Maximum length of this slug, suffix included. |

Any option you leave out comes from `config/pharaonic/sluggable.php`, then from the package default.
