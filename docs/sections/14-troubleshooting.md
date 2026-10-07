## Troubleshooting

### The slug is always `null`

The source is empty, or it isn't a direct attribute. A string source such as `'category.name'` is read as an attribute named `category.name`, not as a relation. Use a callable: `fn ($model) => $model->category?->name ?? ''`.

Also check that `on_create` isn't set to `false` in `config/pharaonic/sluggable.php`.

### The slug didn't change after updating the title

This is the default: `on_update` is `false` so URLs stay stable. Enable it in the config or for that slug with `'on_update' => true`. If you also set the slug column in the same save, your value wins.

### My `$sluggable` property is ignored

The model (or a parent class) defines a `sluggable()` method. The method always takes precedence and the two are never merged. Add the `slug` entry to the method instead.

### `UNIQUE constraint failed` when saving

- The model uses `SoftDeletes` and a trashed row still holds the slug. Enable `include_trashed`.
- `unique` is disabled for a column that has a unique index. Remove the index or enable `unique`.
- Two requests saved the same slug at the same moment. The index did its job: catch the exception and retry the save.

### `Column not found: seo_slug`

Each key returned by `sluggable()` is a column that must exist. Add it with `$table->sluggable('seo_slug')`.

### Mass assignment ignores my slug

Slug columns are added to `$fillable` only when the model already defines `$fillable`. With `$guarded`, make sure the slug column isn't guarded.

### Slugs contain non-Latin characters

That's the default Unicode behavior. Set `ascii_only` to `true` to transliterate them to ASCII.

### Callable source runs extra queries

Callables that read relations load them lazily. When creating many models, load the relation first or pass the value through an attribute.
